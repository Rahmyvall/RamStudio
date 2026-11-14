<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class AboutSocialLinkRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'platform' => ['required', 'string', 'max:100'],
      'label' => ['nullable', 'string', 'max:150'],
      'url' => ['required', 'url', 'max:255'],
      'icon' => ['nullable', 'string', 'max:100'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
