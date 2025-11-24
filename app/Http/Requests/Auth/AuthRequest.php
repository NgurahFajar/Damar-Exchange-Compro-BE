<?php

namespace App\Http\Requests\Auth;

use App\Http\Responses\Auth\AuthResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class AuthRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'remember_me' => 'sometimes|boolean'
        ];
    }

    public function attributes(): array
    {
        return [
            'user' => 'username',
            'password' => 'password',
        ];
    }


    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            AuthResponse::error($validator->errors()->first(), 422)
        );
    }
}
