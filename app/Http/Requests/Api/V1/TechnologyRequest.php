<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class TechnologyRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('technology');

    return [
      'name' => ['required', 'string', 'max:100'],
      'slug' => ['nullable', 'string', 'max:120', Rule::unique('technologies', 'slug')->ignore($id)],
    ];
  }
}
