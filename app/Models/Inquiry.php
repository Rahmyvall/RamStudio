<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inquiry extends Model
{
  /** @use HasFactory<\Database\Factories\InquiryFactory> */
  use HasFactory;

  public const STATUSES = ['new', 'in_progress', 'contacted', 'closed'];

  protected $fillable = [
    'name',
    'email',
    'phone',
    'company',
    'subject',
    'message',
    'budget',
    'status',
  ];

  public function services(): BelongsToMany
  {
    return $this->belongsToMany(Service::class, 'inquiry_services');
  }

  public function scopeStatus(Builder $query, string $status): Builder
  {
    return $query->where('status', $status);
  }
}
