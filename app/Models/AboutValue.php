<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutValue extends Model
{
  /** @use HasFactory<\Database\Factories\AboutValueFactory> */
  use HasFactory;

  /** Allowed values for the `type` column. */
  public const TYPES = ['vision', 'mission', 'value'];

  protected $fillable = [
    'title',
    'description',
    'icon',
    'type',
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

  public function scopeOfType(Builder $query, string $type): Builder
  {
    return $query->where('type', $type);
  }
}
