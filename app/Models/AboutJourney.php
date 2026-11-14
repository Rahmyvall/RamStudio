<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutJourney extends Model
{
  /** @use HasFactory<\Database\Factories\AboutJourneyFactory> */
  use HasFactory;

  protected $fillable = [
    'year',
    'title',
    'description',
    'sort_order',
    'is_active',
  ];

  protected function casts(): array
  {
    return [
      'year' => 'integer',
      'sort_order' => 'integer',
      'is_active' => 'boolean',
    ];
  }

  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }

  public function scopeChronological(Builder $query): Builder
  {
    return $query->orderBy('year')->orderBy('sort_order');
  }
}
