<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutJourney;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AboutJourneyController extends AdminCrudController
{
  protected function model(): string
  {
    return AboutJourney::class;
  }

  protected function moduleTitle(): string
  {
    return 'Perjalanan';
  }

  protected function routePrefix(): string
  {
    return 'admin.about.journeys';
  }

  protected function searchable(): array
  {
    return ['title', 'description'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'year', 'label' => 'Tahun'],
      ['key' => 'title', 'label' => 'Judul'],
      ['key' => 'sort_order', 'label' => 'Urutan'],
      ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'year', 'label' => 'Tahun', 'type' => 'number', 'min' => 1900, 'max' => 2100, 'required' => true],
      ['name' => 'title', 'label' => 'Judul Peristiwa', 'type' => 'text', 'required' => true],
      ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 5],
      ['name' => 'sort_order', 'label' => 'Urutan Tampil', 'type' => 'number', 'default' => 0],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'year' => ['required', 'integer', 'min:1900', 'max:2100'],
      'title' => ['required', 'string', 'max:180'],
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
