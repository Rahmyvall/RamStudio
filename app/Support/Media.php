<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
  /**
   * Build a public URL for a file stored on the "public" disk.
   *
   * Returns a host-relative path (e.g. "/storage/uploads/posts/x.png")
   * instead of an absolute URL, so images keep working regardless of the
   * configured APP_URL, port or host (localhost, 127.0.0.1, LAN, etc.).
   */
  public static function url(?string $path): ?string
  {
    if (! $path) {
      return null;
    }

    // Values already stored as absolute URLs (legacy data) pass through.
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
      return $path;
    }

    // Handle images directly in public/assets
    if (str_starts_with($path, 'assets/')) {
      return '/' . $path;
    }

    return '/storage/' . ltrim($path, '/');
  }

  /**
   * Delete a file from the public disk when it exists.
   */
  public static function delete(?string $path): void
  {
    if (! $path) {
      return;
    }

    Storage::disk('public')->delete($path);
  }
}
