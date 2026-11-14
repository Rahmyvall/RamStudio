<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Slug
{
  /**
   * Build a URL friendly slug that is unique within the given table.
   */
  public static function unique(?string $value, string $table, int|string|null $ignoreId = null, string $column = 'slug'): string
  {
    $base = Str::slug((string) $value);
    $base = $base !== '' ? $base : Str::random(8);

    $slug = $base;
    $suffix = 1;

    while (self::exists($table, $column, $slug, $ignoreId)) {
      $slug = $base . '-' . $suffix++;
    }

    return $slug;
  }

  private static function exists(string $table, string $column, string $slug, int|string|null $ignoreId): bool
  {
    $query = DB::table($table)->where($column, $slug);

    if ($ignoreId !== null) {
      $query->where('id', '!=', $ignoreId);
    }

    return $query->exists();
  }
}
