<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class FaqRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'question' => ['required', 'string', 'max:500'],
      'answer' => ['required', 'string'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
