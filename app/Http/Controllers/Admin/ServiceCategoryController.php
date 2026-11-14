<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceCategory;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceCategoryController extends AdminCrudController
{
  protected function model(): string
  {
    return ServiceCategory::class;
  }

  protected function moduleTitle(): string
  {
    return 'Kategori Layanan';
  }

  protected function routePrefix(): string
  {
    return 'admin.service-categories';
  }

  protected function searchable(): array
  {
    return ['name', 'slug'];
  }

  protected function indexWith(): array
  {
    return ['services'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'name', 'label' => 'Nama'],
      ['key' => 'slug', 'label' => 'Slug', 'type' => 'code'],
      ['key' => 'sort_order', 'label' => 'Urutan'],
      ['key' => 'is_active', 'label' => 'Status', 'type' => 'bool'],
      ['key' => 'services_count', 'label' => 'Layanan', 'type' => 'count', 'relation' => 'services'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'name', 'label' => 'Nama Kategori', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis dari nama.'],
      ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea'],
      ['name' => 'sort_order', 'label' => 'Urutan Tampil', 'type' => 'number', 'default' => 0],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'name' => ['required', 'string', 'max:150'],
      'slug' => ['nullable', 'string', 'max:180', Rule::unique('service_categories', 'slug')->ignore($model?->id)],
      'description' => ['nullable', 'string'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['name'], 'service_categories', $model?->id);
    $data['sort_order'] = $data['sort_order'] ?? 0;
    $data['is_active'] = $request->boolean('is_active');

    return $data;
  }
}
