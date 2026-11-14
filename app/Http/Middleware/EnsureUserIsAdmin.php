<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protects the admin dashboard: the visitor must be authenticated and
 * must own a role that is allowed to access the back-office.
 */
class EnsureUserIsAdmin
{
  /**
   * Roles allowed to access the admin dashboard (see `users.role`).
   *
   * @var array<int, string>
   */
  public const ALLOWED_ROLES = ['admin', 'superadmin'];

  public function handle(Request $request, Closure $next): Response
  {
    $user = $request->user();

    if (! $user) {
      return $request->expectsJson()
        ? response()->json([
          'success' => false,
          'message' => 'Unauthenticated.',
          'errors' => null,
        ], 401)
        : redirect()->guest(route('admin.login'));
    }

    if (! in_array($user->role, self::ALLOWED_ROLES, true)) {
      if ($request->expectsJson() || $request->is('api/*')) {
        return response()->json([
          'success' => false,
          'message' => 'Anda tidak memiliki hak akses admin.',
          'errors' => null,
        ], 403);
      }

      abort(403, 'Anda tidak memiliki hak akses ke halaman admin.');
    }

    return $next($request);
  }
}
