<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'portfolio_category_id' => $this->portfolio_category_id,
      'title' => $this->title,
      'slug' => $this->slug,
      'client_name' => $this->client_name,
      'project_url' => $this->project_url,
      'thumbnail' => \App\Support\Media::url($this->thumbnail),
      'description' => $this->description,
      'project_date' => $this->project_date?->toDateString(),
      'is_featured' => (bool) $this->is_featured,
      'is_published' => (bool) $this->is_published,
      'category' => new PortfolioCategoryResource($this->whenLoaded('category')),
      'images' => PortfolioImageResource::collection($this->whenLoaded('images')),
      'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
