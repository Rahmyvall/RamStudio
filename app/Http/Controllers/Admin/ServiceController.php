<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends AdminCrudController
{
  protected function model(): string
  {
    return Service::class;
  }

  protected function moduleTitle(): string
  {
    return 'Layanan';
  }

  protected function routePrefix(): string
  {
    return 'admin.services';
  }

  protected function searchable(): array
  {
    return ['name', 'slug', 'short_description'];
  }

  protected function indexWith(): array
  {
    return ['category'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'service_category_id',
        'label' => 'Kategori',
        'options' => ServiceCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
      ],
      [
        'name' => 'is_active',
        'label' => 'Status',
        'options' => ['1' => 'Aktif', '0' => 'Nonaktif'],
      ],
    ];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'image', 'label' => 'Gambar', 'type' => 'image'],
      ['key' => 'name', 'label' => 'Nama'],
      ['key' => 'category.name', 'label' => 'Kategori', 'type' => 'relation'],
      ['key' => 'starting_price', 'label' => 'Harga Mulai', 'type' => 'price'],
      ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'service_category_id', 'label' => 'Kategori', 'type' => 'select', 'options_key' => 'categories', 'required' => true],
      ['name' => 'name', 'label' => 'Nama Layanan', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
      ['name' => 'icon', 'label' => 'Ikon (Font Awesome)', 'type' => 'text', 'help' => 'Contoh: fa fa-code'],
      ['name' => 'short_description', 'label' => 'Deskripsi Singkat', 'type' => 'textarea', 'rows' => 2],
      ['name' => 'description', 'label' => 'Deskripsi Lengkap', 'type' => 'textarea', 'rows' => 8],
      ['name' => 'image', 'label' => 'Gambar', 'type' => 'image'],
      ['name' => 'starting_price', 'label' => 'Harga Mulai (Rp)', 'type' => 'number', 'step' => '0.01'],
      ['name' => 'is_featured', 'label' => 'Unggulan', 'type' => 'checkbox'],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function formOptions(): array
  {
    return [
      'categories' => ServiceCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
      'name' => ['required', 'string', 'max:180'],
      'slug' => ['nullable', 'string', 'max:200', Rule::unique('services', 'slug')->ignore($model?->id)],
      'icon' => ['nullable', 'string', 'max:100'],
      'short_description' => ['nullable', 'string', 'max:500'],
      'description' => ['nullable', 'string'],
      'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'starting_price' => ['nullable', 'numeric', 'min:0'],
      'is_featured' => ['nullable', 'boolean'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['name'], 'services', $model?->id);
    $data['is_featured'] = $request->boolean('is_featured');
    $data['is_active'] = $request->boolean('is_active');

    return $data;
  }
}
