<?php

namespace App\Http\Resources;

use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutTeamResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'position' => $this->position,
      'photo' => Media::url($this->photo),
      'bio' => $this->bio,
      'email' => $this->email,
      'linkedin_url' => $this->linkedin_url,
      'instagram_url' => $this->instagram_url,
      'sort_order' => $this->sort_order,
      'is_active' => (bool) $this->is_active,
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
