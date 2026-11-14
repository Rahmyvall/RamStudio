<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class AboutPersonRequest extends ApiFormRequest
{
  /**
   * Shared rules for owners and team members (identical schemas).
   */
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:150'],
      'position' => ['nullable', 'string', 'max:150'],
      'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
      'bio' => ['nullable', 'string'],
      'email' => ['nullable', 'email', 'max:190'],
      'linkedin_url' => ['nullable', 'url', 'max:255'],
      'instagram_url' => ['nullable', 'url', 'max:255'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
