<?php

namespace App\Services\Currency;

use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Currency\CurrencyException;
use Illuminate\Http\UploadedFile;

class CurrencyService
{
    protected CurrencyRepository $repository;

    public function __construct(CurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all active currencies logic
     */
    public function getAllCurrencies(): Collection
    {
        try {
            Log::info('Retrieving all active currencies');
            return $this->repository->getAll();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve currencies', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::systemError($e);
        }
    }

    /**
     * Create a new currency logic
     */
    public function createCurrency(array $data, int $userId): Currency
    {
        try {
            // Validate required fields
            if (!isset($data['currency_code'])) {
                throw new \InvalidArgumentException('Currency code is required');
            }

            Log::info('Starting currency creation process', [
                'currency_code' => $data['currency_code'],
                'user_id' => $userId,
                'received_data' => array_keys($data)
            ]);

            $activeCurrency = $this->repository->findByCurrencyCode($data['currency_code']);
            if ($activeCurrency) {
                throw CurrencyException::validationError('code', 'unique');
            }

            $currencyData = [
                'currency_code' => $data['currency_code'],
                'currency_name' => $data['currency_name'],
                'buy_rate' => $data['buy_rate'] ?? null,
                'sell_rate' => $data['sell_rate'] ?? null,
                'user_id' => $userId,
                'is_deleted' => false
            ];

            // Handle icon if present
            if (isset($data['icon'])) {
                $iconData = $this->handleIconUpload($data['icon']);
                $currencyData['icon_data'] = $iconData['icon_data'];
                $currencyData['icon_type'] = $iconData['icon_type'];
            }

            $currency = $this->repository->create($currencyData);

            Log::info('Currency created successfully', [
                'currency_code' => $currency->currency_code,
                'user_id' => $userId
            ]);

            return $currency;

        } catch (CurrencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Currency creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::createFailed($e);
        }
    }

    /**
     * Update existing currency logic
     */
    public function updateCurrency(string $code, array $data, int $userId): Currency
    {
        try {
            $currency = $this->repository->findByCurrencyCode($code);
            if (!$currency) {
                throw CurrencyException::notFound();
            }

            if ($currency->user_id !== $userId) {
                throw CurrencyException::unauthorized();
            }

            // Handle icon upload if present
            if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
                $iconData = $this->handleIconUpload($data['icon']);
                $data['icon_data'] = $iconData['icon_data'];
                $data['icon_type'] = $iconData['icon_type'];
                unset($data['icon']);
            }

            // Remove null or empty string values
            $data = array_filter($data, function ($value) {
                return $value !== null && $value !== '';
            });

            // Ensure we have data to update
            if (empty($data)) {
                throw CurrencyException::validationError('fields', 'required');
            }

            $updatedCurrency = $this->repository->update($code, $data);

            return $updatedCurrency;

        } catch (CurrencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Currency update failed', [
                'code' => $code,
                'error' => $e->getMessage()
            ]);
            throw CurrencyException::updateFailed($e);
        }
    }

    /**
     * Find currency by code logic
     */
    public function findByCurrencyCode(string $code): Currency
    {
        try {
            Log::info('Finding currency by code', ['currency_code' => $code]);

            $currency = $this->repository->findByCurrencyCode($code);
            if (!$currency) {
                throw CurrencyException::notFound();
            }

            return $currency;
        } catch (CurrencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to find currency', [
                'code' => $code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::systemError($e);
        }
    }

    /**
     * Delete specific currency by code logic
     */
    public function deleteCurrency(string $code, int $userId): bool
    {
        try {
            Log::info('Starting currency deletion process', [
                'currency_code' => $code,
                'user_id' => $userId
            ]);

            $currency = $this->repository->findByCurrencyCode($code);

            if (!$currency) {
                throw CurrencyException::notFound();
            }

            if ($currency->user_id !== $userId) {
                Log::warning('Unauthorized currency deletion attempt', [
                    'currency_code' => $code,
                    'attempted_user_id' => $userId
                ]);
                throw CurrencyException::unauthorized();
            }

            $result = $this->repository->delete($code);

            Log::info('Currency deleted successfully', [
                'currency_code' => $code,
                'user_id' => $userId
            ]);

            return $result;
        } catch (CurrencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to delete currency', [
                'error' => $e->getMessage(),
                'code' => $code,
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::deleteFailed($e);
        }
    }

    /**
     * Delete all currencies logic
     */
    public function deleteAllCurrencies(int $userId): bool
    {
        try {
            Log::info('Starting delete all currencies process', [
                'user_id' => $userId
            ]);

            $result = $this->repository->deleteAll();

            Log::info('All currencies deleted successfully', [
                'user_id' => $userId
            ]);

            return $result;
        } catch (CurrencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to delete all currencies', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::deleteFailed($e);
        }
    }

    /**
     * Handle icon file upload logic
     */
    protected function handleIconUpload(UploadedFile $icon): array
    {
        try {
            if (!$icon->isValid()) {
                throw new \Exception('Invalid file upload');
            }

            $content = file_get_contents($icon->getRealPath());
            if ($content === false) {
                throw new \Exception('Failed to read file content');
            }

            return [
                'icon_data' => $content,
                'icon_type' => $icon->getMimeType()
            ];
        } catch (\Exception $e) {
            Log::error('Icon upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::systemError(
                new \Exception('Failed to process icon file')
            );
        }
    }

    /**
     * Find currency by code including deleted ones logic
     */
    public function findByCurrencyCodeWithTrashed(string $code): ?Currency
    {
        try {
            Log::info('Finding currency by code (including deleted)', [
                'currency_code' => $code
            ]);

            return $this->repository->findWithTrashed($code);
        } catch (\Exception $e) {
            Log::error('Failed to find currency with trashed', [
                'code' => $code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::systemError($e);
        }
    }

    /**
     * Restore a deleted currency logic
     */
    public function restoreDeletedCurrency(array $data, int $userId): Currency
    {
        try {
            if (!isset($data['currency_code'])) {
                throw new \InvalidArgumentException('Currency code is required');
            }

            Log::info('Starting currency restoration process', [
                'currency_code' => $data['currency_code'],
                'user_id' => $userId
            ]);

            // Handle icon if present
            if (isset($data['icon'])) {
                $iconData = $this->handleIconUpload($data['icon']);
                $data['icon_data'] = $iconData['icon_data'];
                $data['icon_type'] = $iconData['icon_type'];
                unset($data['icon']);
            }

            $currency = $this->repository->restore($data['currency_code'], $data);

            Log::info('Currency restored successfully', [
                'currency_code' => $currency->currency_code,
                'user_id' => $userId
            ]);

            return $currency;

        } catch (CurrencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Currency restoration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw CurrencyException::restoreFailed($e);
        }
    }
}
