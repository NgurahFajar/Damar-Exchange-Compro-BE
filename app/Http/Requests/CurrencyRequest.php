<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Responses\CurrencyResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
{
    protected $decoded_input;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->method();
        $rules = [
            'currency_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9]{1,5}$/',
                Rule::unique('currencies')->where(function ($query) {
                    return $query->where('is_deleted', false);
                })
            ],
            'currency_name' => 'required|string|max:255',
            'buy_rate' => 'nullable|numeric|min:0',
            'sell_rate' => 'nullable|numeric|min:0',
            'icon' => 'required|file|mimes:svg,png|max:2048'
        ];

        if ($method === 'PUT' || $method === 'PATCH') {
            return [
                'currency_name' => 'sometimes|required|string|max:255',
                'buy_rate' => 'sometimes|required|numeric|min:0',
                'sell_rate' => 'sometimes|required|numeric|min:0',
                'icon' => 'sometimes|file|mimes:svg|max:2048'
            ];
        }
        return $rules;
    }

    protected function prepareForValidation()
    {
        try {
            // Handle different content types
            $contentType = $this->header('Content-Type');
            $input = [];

            Log::debug('Preparing validation data', [
                'contentType' => $contentType,
                'method' => $this->method(),
                'rawContent' => $this->getContent()
            ]);

            // Handle text/plain content type containing JSON
            if (strpos($contentType, 'text/plain') !== false && $this->getContent()) {
                $jsonData = json_decode($this->getContent(), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $input = $jsonData;
                    Log::debug('Parsed JSON from text/plain', ['parsed_data' => $input]);
                }
            }
            // Handle application/json
            else if ($this->isJson()) {
                $input = $this->json()->all();
            }
            // Handle regular form data
            else {
                $input = $this->all();
            }

            // Filter out null values and empty strings
            $input = array_filter($input, function ($value) {
                return $value !== null && $value !== '';
            });

            // Handle file uploads separately
            if ($this->hasFile('icon')) {
                $input['icon'] = $this->file('icon');
            }

            Log::debug('Prepared input data', [
                'processedInput' => $input
            ]);

            if (!empty($input)) {
                $this->replace($input);
                $this->decoded_input = $input;
            }
        } catch (\Exception $e) {
            Log::error('Error preparing validation data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    public function messages(): array
    {
        return [
            // Required validations
            'currency_code.required' => __('currency.errors.validation.required.code'),
            'currency_name.required' => __('currency.errors.validation.required.name'),
            'icon.required' => __('currency.errors.validation.required.icon'),

            // Format validations
            'currency_code.regex' => __('currency.errors.validation.format.code'),
            'currency_code.max' => __('currency.errors.validation.format.code'),
            'currency_name.max' => __('currency.errors.validation.format.name'),

            // Numeric validations
            'buy_rate.numeric' => __('currency.errors.validation.numeric.buy_rate'),
            'buy_rate.min' => __('currency.errors.validation.numeric.buy_rate'),
            'sell_rate.numeric' => __('currency.errors.validation.numeric.sell_rate'),
            'sell_rate.min' => __('currency.errors.validation.numeric.sell_rate'),

            // File validations
            'icon.file' => __('currency.errors.validation.icon.process_failed'),
            'icon.mimes' => __('currency.errors.validation.icon.process_failed'),
            'icon.max' => __('currency.errors.validation.icon.process_failed'),

            // Unique validation
            'currency_code.unique' => __('currency.errors.validation.unique.code'),
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->toArray();
        Log::warning('Currency validation failed', [
            'errors' => $errors,
            'input' => $this->all()
        ]);

        $firstError = array_values($errors)[0][0] ?? 'Validation failed';

        throw new HttpResponseException(
            CurrencyResponse::error($firstError, 422)
        );
    }

    public function validationData()
    {
        return $this->decoded_input ?? parent::validationData();
    }
}
