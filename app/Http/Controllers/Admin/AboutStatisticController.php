<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutStatistic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AboutStatisticController extends AdminCrudController
{
  protected function model(): string
  {
    return AboutStatistic::class;
  }

  protected function moduleTitle(): string
  {
    return 'Statistik';
  }

  protected function routePrefix(): string
  {
    return 'admin.about.statistics';
  }

  protected function searchable(): array
  {
    return ['label', 'value'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'label', 'label' => 'Label'],
      ['key' => 'value', 'label' => 'Nilai'],
      ['key' => 'suffix', 'label' => 'Suffix'],
      ['key' => 'icon', 'label' => 'Ikon', 'type' => 'code'],
      ['key' => 'sort_order', 'label' => 'Urutan'],
      ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => true, 'help' => 'Contoh: Proyek Selesai'],
      ['name' => 'value', 'label' => 'Nilai', 'type' => 'text', 'required' => true, 'help' => 'Contoh: 120'],
      ['name' => 'suffix', 'label' => 'Suffix', 'type' => 'text', 'help' => 'Contoh: + atau %'],
      ['name' => 'icon', 'label' => 'Ikon (Font Awesome)', 'type' => 'text', 'help' => 'Contoh: fa fa-briefcase'],
      ['name' => 'sort_order', 'label' => 'Urutan Tampil', 'type' => 'number', 'default' => 0],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'label' => ['required', 'string', 'max:150'],
      'value' => ['required', 'string', 'max:50'],
      'suffix' => ['nullable', 'string', 'max:20'],
      'icon' => ['nullable', 'string', 'max:100'],
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
