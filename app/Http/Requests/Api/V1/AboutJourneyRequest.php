<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class AboutJourneyRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'year' => ['required', 'integer', 'min:1900', 'max:2100'],
      'title' => ['required', 'string', 'max:180'],
      'description' => ['nullable', 'string'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
