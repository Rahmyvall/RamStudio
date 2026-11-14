<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class TestimonialRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'client_name' => ['required', 'string', 'max:150'],
      'client_position' => ['nullable', 'string', 'max:150'],
      'company_name' => ['nullable', 'string', 'max:180'],
      'client_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
      'message' => ['required', 'string'],
      'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
      'is_published' => ['nullable', 'boolean'],
    ];
  }
}
