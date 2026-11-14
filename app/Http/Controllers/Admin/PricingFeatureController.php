<?php

namespace App\Http\Controllers\Admin;

use App\Models\PricingFeature;
use App\Models\PricingPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PricingFeatureController extends AdminCrudController
{
  protected function model(): string
  {
    return PricingFeature::class;
  }

  protected function moduleTitle(): string
  {
    return 'Fitur Paket Harga';
  }

  protected function routePrefix(): string
  {
    return 'admin.pricing-features';
  }

  protected function searchable(): array
  {
    return ['feature'];
  }

  protected function indexWith(): array
  {
    return ['package'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'pricing_package_id',
        'label' => 'Paket',
        'options' => PricingPackage::query()->orderBy('name')->pluck('name', 'id')->all(),
      ],
    ];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'feature', 'label' => 'Fitur'],
      ['key' => 'package.name', 'label' => 'Paket', 'type' => 'relation'],
      ['key' => 'is_included', 'label' => 'Termasuk', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'pricing_package_id', 'label' => 'Paket', 'type' => 'select', 'options_key' => 'packages', 'required' => true],
      ['name' => 'feature', 'label' => 'Nama Fitur', 'type' => 'text', 'required' => true],
      ['name' => 'is_included', 'label' => 'Termasuk Dalam Paket', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function formOptions(): array
  {
    return [
      'packages' => PricingPackage::query()->orderBy('name')->pluck('name', 'id')->all(),
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'pricing_package_id' => ['required', 'integer', 'exists:pricing_packages,id'],
      'feature' => ['required', 'string', 'max:255'],
      'is_included' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['is_included'] = $request->boolean('is_included');

    return $data;
  }
}
