<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class PortfolioRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('portfolio');

    return [
      'portfolio_category_id' => ['nullable', 'integer', 'exists:portfolio_categories,id'],
      'title' => ['required', 'string', 'max:180'],
      'slug' => ['nullable', 'string', 'max:200', Rule::unique('portfolios', 'slug')->ignore($id)],
      'client_name' => ['nullable', 'string', 'max:180'],
      'project_url' => ['nullable', 'url', 'max:255'],
      'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'description' => ['nullable', 'string'],
      'project_date' => ['nullable', 'date'],
      'technologies' => ['nullable', 'array'],
      'technologies.*' => ['integer', 'exists:technologies,id'],
      'is_featured' => ['nullable', 'boolean'],
      'is_published' => ['nullable', 'boolean'],
    ];
  }
}
