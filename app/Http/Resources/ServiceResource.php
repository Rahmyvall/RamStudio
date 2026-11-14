<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'service_category_id' => $this->service_category_id,
      'name' => $this->name,
      'slug' => $this->slug,
      'short_description' => $this->short_description,
      'description' => $this->description,
      'icon' => $this->icon,
      'image' => \App\Support\Media::url($this->image),
      'starting_price' => $this->starting_price !== null ? (float) $this->starting_price : null,
      'is_featured' => (bool) $this->is_featured,
      'is_active' => (bool) $this->is_active,
      'category' => new ServiceCategoryResource($this->whenLoaded('category')),
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
