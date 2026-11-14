<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
  /** @use HasFactory<\Database\Factories\PostFactory> */
  use HasFactory;

  public const STATUSES = ['draft', 'published', 'archived'];

  protected $fillable = [
    'post_category_id',
    'user_id',
    'title',
    'slug',
    'excerpt',
    'content',
    'featured_image',
    'status',
    'published_at',
  ];

  protected function casts(): array
  {
    return [
      'published_at' => 'datetime',
    ];
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(PostCategory::class, 'post_category_id');
  }

  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class, 'post_tag');
  }

  public function scopePublished(Builder $query): Builder
  {
    return $query->where('status', 'published');
  }
}
