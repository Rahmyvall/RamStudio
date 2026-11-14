<?php

namespace App\Http\Controllers\Admin;

use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PortfolioImageController extends AdminCrudController
{
  protected function model(): string
  {
    return PortfolioImage::class;
  }

  protected function moduleTitle(): string
  {
    return 'Gambar Portofolio';
  }

  protected function routePrefix(): string
  {
    return 'admin.portfolio-images';
  }

  protected function searchable(): array
  {
    return ['alt_text', 'image_path'];
  }

  protected function indexWith(): array
  {
    return ['portfolio'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'portfolio_id',
        'label' => 'Portofolio',
        'options' => Portfolio::query()->orderBy('title')->pluck('title', 'id')->all(),
      ],
    ];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'image_path', 'label' => 'Gambar', 'type' => 'image'],
      ['key' => 'portfolio.title', 'label' => 'Portofolio', 'type' => 'relation'],
      ['key' => 'alt_text', 'label' => 'Alt Text'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'portfolio_id', 'label' => 'Portofolio', 'type' => 'select', 'options_key' => 'portfolios', 'required' => true],
      ['name' => 'image_path', 'label' => 'Gambar', 'type' => 'image', 'required' => true],
      ['name' => 'alt_text', 'label' => 'Alt Text', 'type' => 'text'],
    ];
  }

  protected function formOptions(): array
  {
    return [
      'portfolios' => Portfolio::query()->orderBy('title')->pluck('title', 'id')->all(),
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'portfolio_id' => ['required', 'integer', 'exists:portfolios,id'],
      'image_path' => [$model ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'alt_text' => ['nullable', 'string', 'max:255'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    // The stored column is `image_path`, while the file input is named
    // `image_path` as well so the base controller stores the path there.
    return $data;
  }
}
