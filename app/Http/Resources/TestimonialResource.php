<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'client_name' => $this->client_name,
      'client_position' => $this->client_position,
      'company_name' => $this->company_name,
      'client_photo' => \App\Support\Media::url($this->client_photo),
      'message' => $this->message,
      'rating' => (int) $this->rating,
      'is_published' => (bool) $this->is_published,
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
