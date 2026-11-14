<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
  /** @use HasFactory<\Database\Factories\TestimonialFactory> */
  use HasFactory;

  protected $fillable = [
    'client_name',
    'client_position',
    'company_name',
    'client_photo',
    'message',
    'rating',
    'is_published',
  ];

  protected function casts(): array
  {
    return [
      'rating' => 'integer',
      'is_published' => 'boolean',
    ];
  }

  public function scopePublished(Builder $query): Builder
  {
    return $query->where('is_published', true);
  }
}
