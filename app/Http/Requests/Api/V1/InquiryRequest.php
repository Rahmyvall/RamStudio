<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use App\Models\Inquiry;

class InquiryRequest extends ApiFormRequest
{
  /**
   * Public submissions only need the contact details; the status is
   * managed by admins and defaults to "new".
   */
  public function rules(): array
  {
    $isUpdate = $this->route('inquiry') !== null;

    $rules = [
      'name' => ['required', 'string', 'max:150'],
      'email' => ['required', 'email', 'max:190'],
      'phone' => ['nullable', 'string', 'max:40'],
      'company' => ['nullable', 'string', 'max:180'],
      'subject' => ['nullable', 'string', 'max:200'],
      'message' => ['required', 'string'],
      'budget' => ['nullable', 'string', 'max:100'],
      'services' => ['nullable', 'array'],
      'services.*' => ['integer', 'exists:services,id'],
    ];

    if ($isUpdate) {
      $rules['status'] = ['nullable', 'string', 'in:' . implode(',', Inquiry::STATUSES)];
    }

    return $rules;
  }
}
