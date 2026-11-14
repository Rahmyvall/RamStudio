<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'email' => ['required', 'email'],
      'password' => ['required', 'string'],
      'device_name' => ['nullable', 'string', 'max:100'],
    ];
  }

  protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
  {
    throw new \Illuminate\Http\Exceptions\HttpResponseException(
      response()->json([
        'success' => false,
        'message' => 'Data yang dikirim tidak valid.',
        'errors' => $validator->errors(),
      ], 422)
    );
  }
}
