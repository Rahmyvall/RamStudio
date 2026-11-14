<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * Base API form request returning validation failures in the standard
 * RAMStudio JSON envelope instead of Laravel's default structure.
 */
abstract class ApiFormRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  protected function failedValidation(Validator $validator): void
  {
    throw new HttpResponseException($this->errorResponse($validator));
  }

  protected function errorResponse(Validator $validator): JsonResponse
  {
    return response()->json([
      'success' => false,
      'message' => 'Data yang dikirim tidak valid.',
      'errors' => $validator->errors(),
    ], 422);
  }

  /**
   * Find the bound route model, used to ignore the current id on unique rules.
   */
  protected function boundModel(string $parameter): ?object
  {
    $value = $this->route($parameter);

    return is_object($value) ? $value : null;
  }

  /**
   * Resolve the identifier of the model bound to the given route parameter.
   */
  protected function boundId(string $parameter): int|string|null
  {
    $value = $this->route($parameter);

    if (is_object($value)) {
      return $value->getKey();
    }

    return is_scalar($value) ? $value : null;
  }
}
