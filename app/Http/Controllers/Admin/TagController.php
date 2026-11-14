<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagController extends AdminCrudController
{
  protected function model(): string
  {
    return Tag::class;
  }

  protected function moduleTitle(): string
  {
    return 'Tag';
  }

  protected function routePrefix(): string
  {
    return 'admin.tags';
  }

  protected function searchable(): array
  {
    return ['name', 'slug'];
  }

  protected function indexWith(): array
  {
    return ['posts'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'name', 'label' => 'Nama'],
      ['key' => 'slug', 'label' => 'Slug', 'type' => 'code'],
      ['key' => 'posts_count', 'label' => 'Artikel', 'type' => 'count', 'relation' => 'posts'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'name', 'label' => 'Nama Tag', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'name' => ['required', 'string', 'max:100'],
      'slug' => ['nullable', 'string', 'max:120', Rule::unique('tags', 'slug')->ignore($model?->id)],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['name'], 'tags', $model?->id);

    return $data;
  }
}
