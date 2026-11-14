<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('tag');

    return [
      'name' => ['required', 'string', 'max:100'],
      'slug' => ['nullable', 'string', 'max:120', Rule::unique('tags', 'slug')->ignore($id)],
    ];
  }
}
