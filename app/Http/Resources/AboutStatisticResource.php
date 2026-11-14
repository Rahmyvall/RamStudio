<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutStatisticResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'label' => $this->label,
      'value' => $this->value,
      'suffix' => $this->suffix,
      'icon' => $this->icon,
      'sort_order' => $this->sort_order,
      'is_active' => (bool) $this->is_active,
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
