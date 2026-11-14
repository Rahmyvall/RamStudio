<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class PostCategoryRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('postCategory');

    return [
      'name' => ['required', 'string', 'max:150'],
      'slug' => ['nullable', 'string', 'max:180', Rule::unique('post_categories', 'slug')->ignore($id)],
    ];
  }
}
