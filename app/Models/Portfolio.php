<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
  /** @use HasFactory<\Database\Factories\PortfolioFactory> */
  use HasFactory;

  protected $fillable = [
    'portfolio_category_id',
    'title',
    'slug',
    'client_name',
    'project_url',
    'thumbnail',
    'description',
    'project_date',
    'is_featured',
    'is_published',
  ];

  protected function casts(): array
  {
    return [
      'project_date' => 'date',
      'is_featured' => 'boolean',
      'is_published' => 'boolean',
    ];
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(PortfolioCategory::class, 'portfolio_category_id');
  }

  public function images(): HasMany
  {
    return $this->hasMany(PortfolioImage::class);
  }

  public function technologies(): BelongsToMany
  {
    return $this->belongsToMany(Technology::class, 'portfolio_technology');
  }

  public function scopePublished(Builder $query): Builder
  {
    return $query->where('is_published', true);
  }

  public function scopeFeatured(Builder $query): Builder
  {
    return $query->where('is_featured', true);
  }
}
