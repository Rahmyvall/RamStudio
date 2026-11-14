<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class AboutProfileRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'company_name' => ['required', 'string', 'max:180'],
      'tagline' => ['nullable', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
      'vision' => ['nullable', 'string'],
      'mission' => ['nullable', 'string'],
      'address' => ['nullable', 'string', 'max:255'],
      'email' => ['nullable', 'email', 'max:190'],
      'phone' => ['nullable', 'string', 'max:40'],
      'founded_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
      'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
      'about_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
    ];
  }
}
