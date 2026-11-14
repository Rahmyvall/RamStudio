<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AboutTeamController extends AdminCrudController
{
  protected function model(): string
  {
    return AboutTeam::class;
  }

  protected function moduleTitle(): string
  {
    return 'Anggota Tim';
  }

  protected function routePrefix(): string
  {
    return 'admin.about.teams';
  }

  protected function searchable(): array
  {
    return ['name', 'position', 'email'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'photo', 'label' => 'Foto', 'type' => 'image'],
      ['key' => 'name', 'label' => 'Nama'],
      ['key' => 'position', 'label' => 'Jabatan'],
      ['key' => 'email', 'label' => 'Email'],
      ['key' => 'sort_order', 'label' => 'Urutan'],
      ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'name', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
      ['name' => 'position', 'label' => 'Jabatan', 'type' => 'text'],
      ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
      ['name' => 'photo', 'label' => 'Foto', 'type' => 'image'],
      ['name' => 'bio', 'label' => 'Biografi', 'type' => 'textarea', 'rows' => 5],
      ['name' => 'linkedin_url', 'label' => 'LinkedIn URL', 'type' => 'url'],
      ['name' => 'instagram_url', 'label' => 'Instagram URL', 'type' => 'url'],
      ['name' => 'sort_order', 'label' => 'Urutan Tampil', 'type' => 'number', 'default' => 0],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'name' => ['required', 'string', 'max:150'],
      'position' => ['nullable', 'string', 'max:150'],
      'email' => ['nullable', 'email', 'max:190'],
      'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
      'bio' => ['nullable', 'string'],
      'linkedin_url' => ['nullable', 'url', 'max:255'],
      'instagram_url' => ['nullable', 'url', 'max:255'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['sort_order'] = $data['sort_order'] ?? 0;
    $data['is_active'] = $request->boolean('is_active');

    return $data;
  }
}
