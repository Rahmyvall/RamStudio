<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutProfile;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The about profile is a single record, so it is edited in place rather
 * than through the generic CRUD listing.
 */
class AboutProfileController extends Controller
{
  public function edit(): View
  {
    return view('admin.about.profile', [
      'title' => 'Profil Perusahaan',
      'profile' => AboutProfile::current() ?? new AboutProfile(),
    ]);
  }

  public function update(Request $request): RedirectResponse
  {
    $data = $request->validate([
      'company_name' => ['required', 'string', 'max:180'],
      'tagline' => ['nullable', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
      'vision' => ['nullable', 'string'],
      'mission' => ['nullable', 'string'],
      'address' => ['nullable', 'string', 'max:255'],
      'email' => ['nullable', 'email', 'max:190'],
      'phone' => ['nullable', 'string', 'max:40'],
      'founded_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
      'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
      'about_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
    ]);

    $profile = AboutProfile::current() ?? new AboutProfile();

    foreach (['logo', 'hero_image', 'about_image'] as $field) {
      if ($request->hasFile($field)) {
        Media::delete($profile->getAttribute($field));
        $data[$field] = $request->file($field)->store('uploads/about', 'public');
      } else {
        unset($data[$field]);
      }
    }

    $profile->fill($data)->save();

    return redirect()
      ->route('admin.about.profile.edit')
      ->with('success', 'Profil perusahaan berhasil diperbarui.');
  }
}
