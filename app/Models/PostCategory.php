<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The `post_categories` table has no timestamp columns.
 */
class PostCategory extends Model
{
  /** @use HasFactory<\Database\Factories\PostCategoryFactory> */
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'name',
    'slug',
  ];

  public function posts(): HasMany
  {
    return $this->hasMany(Post::class);
  }
}
