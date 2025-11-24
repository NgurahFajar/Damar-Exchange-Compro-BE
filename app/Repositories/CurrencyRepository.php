<?php

namespace App\Repositories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Currency\CurrencyException;

class CurrencyRepository
{
    protected $model;

    public function __construct(Currency $model)
    {
        $this->model = $model;
    }

    /**
     * Get all active currencies
     */
    public function getAll(): Collection
    {
        Log::debug('Query before execution', [
            'sql' => $this->model
                ->where('is_deleted', false)
                ->orderBy('created_at', 'desc')
                ->toSql(),
            'bindings' => $this->model
                ->where('is_deleted', false)
                ->orderBy('created_at', 'desc')
                ->getBindings()
        ]);

        $currencies = $this->model
            ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->get();

        Log::debug('Query results', [
            'count' => $currencies->count(),
            'data' => $currencies->toArray()
        ]);

        return $currencies;
    }

    /**
     * Find currency by code, including deleted if specified
     */
    public function findByCurrencyCode(string $code, bool $withDeleted = false): ?Currency
    {
        $query = $this->model->where('currency_code', $code);

        if (!$withDeleted) {
            $query->where('is_deleted', false);
        }

        Log::debug('Finding currency query', [
            'code' => $code,
            'with_deleted' => $withDeleted,
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $currency = $query->first();

        Log::debug('Currency find result', [
            'code' => $code,
            'found' => $currency ? true : false,
            'is_deleted' => $currency ? $currency->is_deleted : null
        ]);

        return $currency;
    }

    /**
     * Find currency including deleted ones
     */
    public function findWithTrashed(string $code): ?Currency
    {
        return $this->findByCurrencyCode($code, true);
    }

    /**
     * Create new currency
     */
    public function create(array $data): Currency
    {
        try {
            // Create new currency
            $data['is_deleted'] = false;
            Log::debug('Creating new currency', [
                'code' => $data['currency_code'],
                'data' => array_keys($data)
            ]);

            return $this->model->create($data);

        } catch (\Exception $e) {
            Log::error('Repository: Create currency failed', [
                'error' => $e->getMessage(),
                'data' => array_keys($data)
            ]);
            throw CurrencyException::createFailed($e);
        }
    }

    /**
     * Update currency
     */
    public function update(string $code, array $data): ?Currency
    {
        // Find currency with trashed for update/restore operations
        $currency = $this->findWithTrashed($code);
        if (!$currency) {
            throw CurrencyException::notFound();
        }

        try {
            Log::debug('Updating currency', [
                'code' => $code,
                'current_status' => $currency->is_deleted,
                'update_data' => array_keys($data)
            ]);

            if (!isset($data['icon_data'])) {
                unset($data['icon_data']);
                unset($data['icon_type']);
            }

            $currency->fill($data);
            $currency->save();

            return $currency->fresh();
        } catch (\Exception $e) {
            Log::error('Repository: Update currency failed', [
                'error' => $e->getMessage(),
                'code' => $code,
                'data' => array_keys($data)
            ]);
            throw CurrencyException::updateFailed($e);
        }
    }

    /**
     * Restore deleted currency
     */
    public function restore(string $code, array $data): Currency
    {
        try {
            $currency = $this->findWithTrashed($code);
            if (!$currency) {
                throw CurrencyException::notFound();
            }

            if (!$currency->is_deleted) {
                throw CurrencyException::validationError('code', 'unique');
            }

            Log::debug('Restoring deleted currency', [
                'code' => $code,
                'data' => array_keys($data)
            ]);

            $data['is_deleted'] = false;
            return $this->update($code, $data);

        } catch (\Exception $e) {
            Log::error('Repository: Restore currency failed', [
                'error' => $e->getMessage(),
                'code' => $code
            ]);
            throw CurrencyException::restoreFailed($e);
        }
    }

    /**
     * Soft delete currency
     */
    public function delete(string $code): bool
    {
        try {
            $currency = $this->findByCurrencyCode($code);
            if (!$currency) {
                throw CurrencyException::notFound();
            }

            Log::debug('Soft deleting currency', [
                'code' => $code,
                'current_status' => $currency->is_deleted
            ]);

            $currency->is_deleted = true;
            return $currency->save();

        } catch (CurrencyException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Repository: Delete currency failed', [
                'error' => $e->getMessage(),
                'code' => $code
            ]);
            throw CurrencyException::deleteFailed($e);
        }
    }

    /**
     * Delete all currencies
     */
    public function deleteAll(): bool
    {
        try {
            Log::debug('Attempting to delete all currencies');

            $result = $this->model
                ->where('is_deleted', false)
                ->update(['is_deleted' => true]);

            Log::debug('Delete all currencies result', [
                'affected_rows' => $result
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Repository: Delete all currencies failed', [
                'error' => $e->getMessage()
            ]);
            throw CurrencyException::deleteFailed($e);
        }
    }

    /**
     * Check if currency exists (including deleted)
     */
    public function checkExists(string $code): bool
    {
        $exists = $this->model->where('currency_code', $code)->exists();

        Log::debug('Checking currency existence', [
            'code' => $code,
            'exists' => $exists
        ]);

        return $exists;
    }

    /**
     * Check if currency is deleted
     */
    public function isDeleted(string $code): bool
    {
        $currency = $this->findWithTrashed($code);
        $isDeleted = $currency ? $currency->is_deleted : false;

        Log::debug('Checking if currency is deleted', [
            'code' => $code,
            'is_deleted' => $isDeleted
        ]);

        return $isDeleted;
    }
}
