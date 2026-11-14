<?php

namespace App\Http\Controllers\Admin;

use App\Models\PortfolioCategory;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PortfolioCategoryController extends AdminCrudController
{
  protected function model(): string
  {
    return PortfolioCategory::class;
  }

  protected function moduleTitle(): string
  {
    return 'Kategori Portofolio';
  }

  protected function routePrefix(): string
  {
    return 'admin.portfolio-categories';
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
      ['key' => 'portfolios_count', 'label' => 'Portofolio', 'type' => 'count', 'relation' => 'portfolios'],
      ['key' => 'created_at', 'label' => 'Dibuat', 'type' => 'date'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'name', 'label' => 'Nama Kategori', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'name' => ['required', 'string', 'max:150'],
      'slug' => ['nullable', 'string', 'max:180', Rule::unique('portfolio_categories', 'slug')->ignore($model?->id)],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['name'], 'portfolio_categories', $model?->id);

    return $data;
  }
}
