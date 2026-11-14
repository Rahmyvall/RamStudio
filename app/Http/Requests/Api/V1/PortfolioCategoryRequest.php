<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class PortfolioCategoryRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('portfolioCategory');

    return [
      'name' => ['required', 'string', 'max:150'],
      'slug' => ['nullable', 'string', 'max:180', Rule::unique('portfolio_categories', 'slug')->ignore($id)],
    ];
  }
}
