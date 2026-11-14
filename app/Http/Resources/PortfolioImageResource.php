<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioImageResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'portfolio_id' => $this->portfolio_id,
      'image_path' => $this->image_path,
      'image_url' => \App\Support\Media::url($this->image_path),
      'alt_text' => $this->alt_text,
      'portfolio' => new PortfolioResource($this->whenLoaded('portfolio')),
    ];
  }
}
