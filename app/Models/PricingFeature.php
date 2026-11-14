<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `pricing_features` table has no timestamp columns.
 */
class PricingFeature extends Model
{
  /** @use HasFactory<\Database\Factories\PricingFeatureFactory> */
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'pricing_package_id',
    'feature',
    'is_included',
  ];

  protected function casts(): array
  {
    return [
      'is_included' => 'boolean',
    ];
  }

  public function package(): BelongsTo
  {
    return $this->belongsTo(PricingPackage::class, 'pricing_package_id');
  }
}
