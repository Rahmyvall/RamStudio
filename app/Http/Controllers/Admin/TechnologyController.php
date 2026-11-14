<?php

namespace App\Http\Controllers\Admin;

use App\Models\Technology;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TechnologyController extends AdminCrudController
{
  protected function model(): string
  {
    return Technology::class;
  }

  protected function moduleTitle(): string
  {
    return 'Teknologi';
  }

  protected function routePrefix(): string
  {
    return 'admin.technologies';
  }

  protected function searchable(): array
  {
    return ['name', 'slug'];
  }

  protected function indexWith(): array
  {
    return ['portfolios'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'name', 'label' => 'Nama'],
      ['key' => 'slug', 'label' => 'Slug', 'type' => 'code'],
      ['key' => 'portfolios_count', 'label' => 'Dipakai', 'type' => 'count', 'relation' => 'portfolios'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'name', 'label' => 'Nama Teknologi', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'name' => ['required', 'string', 'max:100'],
      'slug' => ['nullable', 'string', 'max:120', Rule::unique('technologies', 'slug')->ignore($model?->id)],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['name'], 'technologies', $model?->id);

    return $data;
  }
}
