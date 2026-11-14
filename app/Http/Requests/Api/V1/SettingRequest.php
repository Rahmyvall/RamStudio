<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('setting');

    return [
      'key' => ['required', 'string', 'max:150', Rule::unique('settings', 'key')->ignore($id)],
      'value' => ['nullable', 'string'],
      'group' => ['nullable', 'string', 'max:100'],
      'type' => ['nullable', Rule::in(['text', 'textarea', 'image', 'boolean', 'json'])],
    ];
  }
}
