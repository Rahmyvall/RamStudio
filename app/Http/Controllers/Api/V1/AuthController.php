<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  /**
   * Issue a Sanctum personal access token for an admin user.
   */
  public function login(LoginRequest $request): JsonResponse
  {
    $user = User::query()->where('email', $request->string('email'))->first();

    if (! $user || ! Hash::check($request->string('password'), $user->password)) {
      return response()->json([
        'success' => false,
        'message' => 'Kredensial tidak valid.',
        'errors' => null,
      ], 401);
    }

    if (! $user->isAdmin()) {
      return response()->json([
        'success' => false,
        'message' => 'Akun ini tidak memiliki hak akses admin.',
        'errors' => null,
      ], 403);
    }

    $token = $user->createToken($request->input('device_name', 'api-token'))->plainTextToken;

    return response()->json([
      'success' => true,
      'message' => 'Login berhasil.',
      'data' => [
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => new UserResource($user),
      ],
    ]);
  }

  public function me(Request $request): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => 'Data pengguna berhasil diambil.',
      'data' => new UserResource($request->user()),
    ]);
  }

  public function logout(Request $request): JsonResponse
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'success' => true,
      'message' => 'Logout berhasil.',
      'data' => null,
    ]);
  }
}
