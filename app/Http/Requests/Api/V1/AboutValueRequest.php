<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use App\Models\AboutValue;
use Illuminate\Validation\Rule;

class AboutValueRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:180'],
      'description' => ['nullable', 'string'],
      'icon' => ['nullable', 'string', 'max:100'],
      'type' => ['required', Rule::in(AboutValue::TYPES)],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
