<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingPackageResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'slug' => $this->slug,
      'description' => $this->description,
      'price' => $this->price !== null ? (float) $this->price : null,
      'price_label' => $this->price_label,
      'is_popular' => (bool) $this->is_popular,
      'is_active' => (bool) $this->is_active,
      'features' => PricingFeatureResource::collection($this->whenLoaded('features')),
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
