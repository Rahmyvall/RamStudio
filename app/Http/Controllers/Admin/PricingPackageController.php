<?php

namespace App\Http\Controllers\Admin;

use App\Models\PricingPackage;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PricingPackageController extends AdminCrudController
{
  protected function model(): string
  {
    return PricingPackage::class;
  }

  protected function moduleTitle(): string
  {
    return 'Paket Harga';
  }

  protected function routePrefix(): string
  {
    return 'admin.pricing-packages';
  }

  protected function searchable(): array
  {
    return ['name', 'slug'];
  }

  protected function indexWith(): array
  {
    return ['features'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'name', 'label' => 'Nama Paket'],
      ['key' => 'price', 'label' => 'Harga', 'type' => 'price'],
      ['key' => 'price_label', 'label' => 'Label Harga'],
      ['key' => 'features_count', 'label' => 'Fitur', 'type' => 'count', 'relation' => 'features'],
      ['key' => 'is_popular', 'label' => 'Populer', 'type' => 'bool'],
      ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'name', 'label' => 'Nama Paket', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
      ['name' => 'price', 'label' => 'Harga (Rp)', 'type' => 'number', 'step' => '0.01'],
      ['name' => 'price_label', 'label' => 'Label Harga', 'type' => 'text', 'help' => 'Contoh: Mulai dari / per bulan'],
      ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 4],
      ['name' => 'is_popular', 'label' => 'Tandai Populer', 'type' => 'checkbox'],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'name' => ['required', 'string', 'max:150'],
      'slug' => ['nullable', 'string', 'max:180', Rule::unique('pricing_packages', 'slug')->ignore($model?->id)],
      'price' => ['nullable', 'numeric', 'min:0'],
      'price_label' => ['nullable', 'string', 'max:100'],
      'description' => ['nullable', 'string'],
      'is_popular' => ['nullable', 'boolean'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['name'], 'pricing_packages', $model?->id);
    $data['is_popular'] = $request->boolean('is_popular');
    $data['is_active'] = $request->boolean('is_active');

    return $data;
  }
}
