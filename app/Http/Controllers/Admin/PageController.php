<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends AdminCrudController
{
  protected function model(): string
  {
    return Page::class;
  }

  protected function moduleTitle(): string
  {
    return 'Halaman';
  }

  protected function routePrefix(): string
  {
    return 'admin.pages';
  }

  protected function searchable(): array
  {
    return ['title', 'slug', 'meta_title'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'is_published',
        'label' => 'Publikasi',
        'options' => ['1' => 'Publik', '0' => 'Draft'],
      ],
    ];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'title', 'label' => 'Judul'],
      ['key' => 'slug', 'label' => 'Slug', 'type' => 'code'],
      ['key' => 'is_published', 'label' => 'Publik', 'type' => 'bool'],
      ['key' => 'updated_at', 'label' => 'Diperbarui', 'type' => 'date'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'title', 'label' => 'Judul Halaman', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
      ['name' => 'content', 'label' => 'Konten', 'type' => 'textarea', 'rows' => 12],
      ['name' => 'meta_title', 'label' => 'Meta Title', 'type' => 'text'],
      ['name' => 'meta_description', 'label' => 'Meta Description', 'type' => 'textarea', 'rows' => 3],
      ['name' => 'is_published', 'label' => 'Publikasikan', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'title' => ['required', 'string', 'max:180'],
      'slug' => ['nullable', 'string', 'max:200', Rule::unique('pages', 'slug')->ignore($model?->id)],
      'content' => ['nullable', 'string'],
      'meta_title' => ['nullable', 'string', 'max:255'],
      'meta_description' => ['nullable', 'string', 'max:500'],
      'is_published' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['title'], 'pages', $model?->id);
    $data['is_published'] = $request->boolean('is_published');

    return $data;
  }
}
