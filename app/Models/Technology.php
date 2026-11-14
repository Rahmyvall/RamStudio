<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * The `technologies` table has no timestamp columns.
 */
class Technology extends Model
{
  /** @use HasFactory<\Database\Factories\TechnologyFactory> */
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'name',
    'slug',
  ];

  public function portfolios(): BelongsToMany
  {
    return $this->belongsToMany(Portfolio::class, 'portfolio_technology');
  }
}
