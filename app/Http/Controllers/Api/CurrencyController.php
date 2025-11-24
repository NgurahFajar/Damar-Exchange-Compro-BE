<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use App\Http\Responses\CurrencyResponse;
use App\Services\Currency\CurrencyService;
use App\Http\Resources\CurrencyResource;
use App\Exceptions\Currency\CurrencyException;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $currencies = $this->currencyService->getAllCurrencies();

            if ($currencies->isEmpty()) {
                return CurrencyResponse::success([], 'currency.success.no_records');
            }

            // Use resource collection for better response handling
            return CurrencyResponse::success(
                CurrencyResource::collection($currencies),
                'currency.success.retrieved'
            );

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(CurrencyRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $requiredFields = ['currency_code', 'currency_name'];
            foreach ($requiredFields as $field) {
                if (!isset($validatedData[$field])) {
                    throw CurrencyException::validationError(
                        str_replace('currency_', '', $field)
                    );
                }
            }

            // Check if currency exists (including deleted)
            $existingCurrency = $this->currencyService->findByCurrencyCodeWithTrashed($validatedData['currency_code']);

            if ($existingCurrency) {
                if ($existingCurrency->is_deleted) {
                    // Handle icon validation for restoration
                    if (!$request->hasFile('icon')) {
                        throw CurrencyException::validationError('icon');
                    }

                    $currency = $this->currencyService->restoreDeletedCurrency(
                        $validatedData,
                        auth()->id()
                    );

                    return CurrencyResponse::restored($currency);
                } else {
                    throw CurrencyException::validationError('code', 'unique');
                }
            }

            if (!$request->hasFile('icon')) {
                throw CurrencyException::validationError('icon');
            }

            $currency = $this->currencyService->createCurrency(
                $validatedData,
                auth()->id()
            );

            return CurrencyResponse::created($currency);

        } catch (CurrencyException $e) {
            return CurrencyResponse::error($e->getMessage(), $e->getCode(), $e->getData());
        } catch (\Exception $e) {
            Log::error('Currency store failed', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return CurrencyResponse::error('currency.errors.create_failed', 500);
        }
    }

    public function show($currency): \Illuminate\Http\JsonResponse
    {
        try {
            $currencyData = $this->currencyService->findByCurrencyCode($currency);

            if (!$currencyData) {
                return CurrencyResponse::notFound();
            }

            return CurrencyResponse::success(
                $currencyData,
                'currency.success.retrieved'
            );
        } catch (CurrencyException $e) {
            return CurrencyResponse::error($e->getMessage(), $e->getCode(), $e->getData() ?? []);
        } catch (\Exception $e) {

            return CurrencyResponse::error('currency.errors.system_error', 500, []);
        }
    }

    public function update(CurrencyRequest $request, $currency): \Illuminate\Http\JsonResponse
    {
        try {
            $input = $request->validated();

            if (empty($input)) {

                return CurrencyResponse::error('currency.errors.validation.no_updates', 422);
            }

            $updatedCurrency = $this->currencyService->updateCurrency(
                $currency,
                $input,
                auth()->id()
            );

            return CurrencyResponse::success(
                $updatedCurrency,
                'currency.success.updated'
            );

        } catch (CurrencyException $e) {
            return CurrencyResponse::error($e->getMessage(), $e->getCode(), $e->getData());
        } catch (\Exception $e) {

            return CurrencyResponse::error('currency.errors.update_failed', 500);
        }
    }

    public function destroy($currency): \Illuminate\Http\JsonResponse
    {
        try {
            $this->currencyService->deleteCurrency($currency, auth()->id());

            return CurrencyResponse::success(
                null,
                'currency.success.deleted'
            );
        } catch (CurrencyException $e) {
            return CurrencyResponse::error($e->getMessage(), $e->getCode(), $e->getData());
        } catch (\Exception $e) {
            return CurrencyResponse::error('currency.errors.delete_failed', 500);
        }
    }

    public function deleteAll(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->currencyService->deleteAllCurrencies(auth()->id());

            return CurrencyResponse::success(
                null,
                'currency.success.deleted_all'
            );
        } catch (CurrencyException $e) {
            return CurrencyResponse::error($e->getMessage(), $e->getCode(), $e->getData());
        } catch (\Exception $e) {
            return CurrencyResponse::error('currency.errors.delete_failed', 500);
        }
    }
}
