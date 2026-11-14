<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
  /** @use HasFactory<\Database\Factories\ServiceFactory> */
  use HasFactory;

  protected $fillable = [
    'service_category_id',
    'name',
    'slug',
    'short_description',
    'description',
    'icon',
    'image',
    'starting_price',
    'is_featured',
    'is_active',
  ];

  protected function casts(): array
  {
    return [
      'starting_price' => 'decimal:2',
      'is_featured' => 'boolean',
      'is_active' => 'boolean',
    ];
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(ServiceCategory::class, 'service_category_id');
  }

  public function inquiries(): BelongsToMany
  {
    return $this->belongsToMany(Inquiry::class, 'inquiry_services');
  }

  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }

  public function scopeFeatured(Builder $query): Builder
  {
    return $query->where('is_featured', true);
  }
}
