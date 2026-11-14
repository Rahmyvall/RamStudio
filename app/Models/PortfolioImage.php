<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The `portfolio_images` table has no timestamps and no primary key other
 * than its auto-increment `id`, so timestamps are disabled explicitly.
 */
class PortfolioImage extends Model
{
  /** @use HasFactory<\Database\Factories\PortfolioImageFactory> */
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'portfolio_id',
    'image_path',
    'alt_text',
  ];

  public function portfolio(): BelongsTo
  {
    return $this->belongsTo(Portfolio::class);
  }
}
