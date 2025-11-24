<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Responses\ImageResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->method();

        $rules = [
            'image' => 'required|file|max:2048|mimes:jpeg,png,jpg,gif'
        ];

        if ($method === 'PUT' || $method === 'PATCH') {
            $rules['image'] = 'required|file|max:2048|mimes:jpeg,png,jpg,gif';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.required' => 'Image file is required',
            'image.file' => 'Upload must be a valid file',
            'image.max' => 'Image must be less than 2MB',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif'
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ImageResponse::error($validator->errors()->first(), 422)
        );
    }
}
