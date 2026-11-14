<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('service');

    return [
      'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
      'name' => ['required', 'string', 'max:180'],
      'slug' => ['nullable', 'string', 'max:200', Rule::unique('services', 'slug')->ignore($id)],
      'short_description' => ['nullable', 'string', 'max:500'],
      'description' => ['nullable', 'string'],
      'icon' => ['nullable', 'string', 'max:100'],
      'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'starting_price' => ['nullable', 'numeric', 'min:0'],
      'is_featured' => ['nullable', 'boolean'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }
}
