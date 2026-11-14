<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('page');

    return [
      'title' => ['required', 'string', 'max:180'],
      'slug' => ['nullable', 'string', 'max:200', Rule::unique('pages', 'slug')->ignore($id)],
      'content' => ['nullable', 'string'],
      'meta_title' => ['nullable', 'string', 'max:255'],
      'meta_description' => ['nullable', 'string', 'max:500'],
      'is_published' => ['nullable', 'boolean'],
    ];
  }
}
