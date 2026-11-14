<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingFeatureResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'pricing_package_id' => $this->pricing_package_id,
      'feature' => $this->feature,
      'is_included' => (bool) $this->is_included,
      'package' => new PricingPackageResource($this->whenLoaded('package')),
    ];
  }
}
