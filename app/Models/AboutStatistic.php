<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStatistic extends Model
{
  /** @use HasFactory<\Database\Factories\AboutStatisticFactory> */
  use HasFactory;

  protected $fillable = [
    'label',
    'value',
    'suffix',
    'icon',
    'sort_order',
    'is_active',
  ];

  protected function casts(): array
  {
    return [
      'sort_order' => 'integer',
      'is_active' => 'boolean',
    ];
  }

  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }
}
