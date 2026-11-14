<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AboutValueController extends AdminCrudController
{
  protected function model(): string
  {
    return AboutValue::class;
  }

  protected function moduleTitle(): string
  {
    return 'Visi, Misi & Nilai';
  }

  protected function routePrefix(): string
  {
    return 'admin.about.values';
  }

  protected function searchable(): array
  {
    return ['title', 'description'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'type',
        'label' => 'Tipe',
        'options' => ['vision' => 'Visi', 'mission' => 'Misi', 'value' => 'Nilai'],
      ],
    ];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'title', 'label' => 'Judul'],
      ['key' => 'type', 'label' => 'Tipe', 'type' => 'badge'],
      ['key' => 'icon', 'label' => 'Ikon', 'type' => 'code'],
      ['key' => 'sort_order', 'label' => 'Urutan'],
      ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
      ['name' => 'type', 'label' => 'Tipe', 'type' => 'select', 'options' => [
        'vision' => 'Visi',
        'mission' => 'Misi',
        'value' => 'Nilai',
      ], 'default' => 'value', 'required' => true],
      ['name' => 'icon', 'label' => 'Ikon (Font Awesome)', 'type' => 'text', 'help' => 'Contoh: fa fa-star'],
      ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 5],
      ['name' => 'sort_order', 'label' => 'Urutan Tampil', 'type' => 'number', 'default' => 0],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'title' => ['required', 'string', 'max:180'],
      'type' => ['required', Rule::in(AboutValue::TYPES)],
      'icon' => ['nullable', 'string', 'max:100'],
      'description' => ['nullable', 'string'],
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
