<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class AboutStatisticRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'label' => ['required', 'string', 'max:150'],
      'value' => ['required', 'string', 'max:50'],
      'suffix' => ['nullable', 'string', 'max:20'],
      'icon' => ['nullable', 'string', 'max:100'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
