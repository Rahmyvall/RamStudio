<?php

namespace App\Http\Resources;

use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutProfileResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'company_name' => $this->company_name,
      'tagline' => $this->tagline,
      'description' => $this->description,
      'vision' => $this->vision,
      'mission' => $this->mission,
      'logo' => Media::url($this->logo),
      'hero_image' => Media::url($this->hero_image),
      'about_image' => Media::url($this->about_image),
      'address' => $this->address,
      'email' => $this->email,
      'phone' => $this->phone,
      'founded_year' => $this->founded_year,
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
