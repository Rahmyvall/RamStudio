<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingPackage extends Model
{
  /** @use HasFactory<\Database\Factories\PricingPackageFactory> */
  use HasFactory;

  protected $fillable = [
    'name',
    'slug',
    'description',
    'price',
    'price_label',
    'is_popular',
    'is_active',
  ];

  protected function casts(): array
  {
    return [
      'price' => 'decimal:2',
      'is_popular' => 'boolean',
      'is_active' => 'boolean',
    ];
  }

  public function features(): HasMany
  {
    return $this->hasMany(PricingFeature::class);
  }

  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }
}
