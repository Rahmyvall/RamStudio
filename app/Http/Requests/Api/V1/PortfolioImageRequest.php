<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class PortfolioImageRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $isUpdate = $this->route('portfolioImage') !== null;

    return [
      'portfolio_id' => ['required', 'integer', 'exists:portfolios,id'],
      'image_path' => [$isUpdate ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'alt_text' => ['nullable', 'string', 'max:255'],
    ];
  }
}
