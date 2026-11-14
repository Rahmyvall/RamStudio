<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class PricingFeatureRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'pricing_package_id' => ['required', 'integer', 'exists:pricing_packages,id'],
      'feature' => ['required', 'string', 'max:255'],
      'is_included' => ['nullable', 'boolean'],
    ];
  }
}
