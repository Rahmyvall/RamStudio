<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class PricingPackageRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('pricingPackage');

    return [
      'name' => ['required', 'string', 'max:150'],
      'slug' => ['nullable', 'string', 'max:180', Rule::unique('pricing_packages', 'slug')->ignore($id)],
      'description' => ['nullable', 'string'],
      'price' => ['nullable', 'numeric', 'min:0'],
      'price_label' => ['nullable', 'string', 'max:100'],
      'is_popular' => ['nullable', 'boolean'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
