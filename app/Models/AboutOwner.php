<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutOwner extends Model
{
  /** @use HasFactory<\Database\Factories\AboutOwnerFactory> */
  use HasFactory;

  protected $fillable = [
    'name',
    'position',
    'photo',
    'bio',
    'email',
    'linkedin_url',
    'instagram_url',
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
