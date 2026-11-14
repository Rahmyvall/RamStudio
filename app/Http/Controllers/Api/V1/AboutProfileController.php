<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AboutProfileRequest;
use App\Http\Resources\AboutProfileResource;
use App\Models\AboutProfile;
use App\Support\Media;
use Illuminate\Http\JsonResponse;

class AboutProfileController extends Controller
{
  /**
   * Public: return the single company profile record.
   */
  public function show(): JsonResponse
  {
    $profile = AboutProfile::current();

    if (! $profile) {
      return response()->json([
        'success' => false,
        'message' => 'Profil perusahaan belum tersedia.',
        'errors' => null,
      ], 404);
    }

    return response()->json([
      'success' => true,
      'message' => 'Profil perusahaan berhasil diambil.',
      'data' => new AboutProfileResource($profile),
    ]);
  }

  /**
   * Admin: create or update the single profile record.
   */
  public function update(AboutProfileRequest $request): JsonResponse
  {
    $profile = AboutProfile::current() ?? new AboutProfile();
    $data = $request->validated();

    foreach (['logo', 'hero_image'] as $field) {
      if ($request->hasFile($field)) {
        Media::delete($profile->getAttribute($field));
        $data[$field] = $request->file($field)->store('uploads/about', 'public');
      } else {
        unset($data[$field]);
      }
    }

    if ($request->hasFile('about_image')) {
      Media::delete($profile->getAttribute('about_image'));
      $data['about_image'] = $request->file('about_image')->store('uploads/about', 'public');
    } else {
      unset($data['about_image']);
    }

    $profile->fill($data)->save();

    return response()->json([
      'success' => true,
      'message' => 'Profil perusahaan berhasil diperbarui.',
      'data' => new AboutProfileResource($profile->fresh()),
    ]);
  }
}
