<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends AdminCrudController
{
  protected function model(): string
  {
    return Post::class;
  }

  protected function moduleTitle(): string
  {
    return 'Artikel';
  }

  protected function routePrefix(): string
  {
    return 'admin.posts';
  }

  protected function searchable(): array
  {
    return ['title', 'slug', 'excerpt'];
  }

  protected function indexWith(): array
  {
    return ['category', 'author', 'tags'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'status',
        'label' => 'Status',
        'options' => ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'],
      ],
      [
        'name' => 'post_category_id',
        'label' => 'Kategori',
        'options' => PostCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
      ],
    ];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'featured_image', 'label' => 'Gambar', 'type' => 'image'],
      ['key' => 'title', 'label' => 'Judul'],
      ['key' => 'category.name', 'label' => 'Kategori', 'type' => 'relation'],
      ['key' => 'author.name', 'label' => 'Penulis', 'type' => 'relation'],
      ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
      ['key' => 'published_at', 'label' => 'Terbit', 'type' => 'date'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'title', 'label' => 'Judul Artikel', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
      ['name' => 'post_category_id', 'label' => 'Kategori', 'type' => 'select', 'options_key' => 'categories'],
      ['name' => 'excerpt', 'label' => 'Ringkasan', 'type' => 'textarea', 'rows' => 3, 'max' => 500],
      ['name' => 'content', 'label' => 'Konten', 'type' => 'textarea', 'rows' => 14, 'required' => true],
      ['name' => 'featured_image', 'label' => 'Gambar Utama', 'type' => 'image'],
      ['name' => 'tags', 'label' => 'Tag', 'type' => 'multiselect', 'options_key' => 'tags'],
      ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'], 'default' => 'draft'],
      ['name' => 'published_at', 'label' => 'Tanggal Terbit', 'type' => 'datetime-local'],
    ];
  }

  protected function formOptions(): array
  {
    return [
      'categories' => PostCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
      'tags' => Tag::query()->orderBy('name')->pluck('name', 'id')->all(),
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'title' => ['required', 'string', 'max:220'],
      'slug' => ['nullable', 'string', 'max:240', Rule::unique('posts', 'slug')->ignore($model?->id)],
      'post_category_id' => ['nullable', 'integer', 'exists:post_categories,id'],
      'excerpt' => ['nullable', 'string', 'max:500'],
      'content' => ['required', 'string'],
      'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
      'tags' => ['nullable', 'array'],
      'tags.*' => ['integer', 'exists:tags,id'],
      'status' => ['required', Rule::in(Post::STATUSES)],
      'published_at' => ['nullable', 'date'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    unset($data['tags']);

    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['title'], 'posts', $model?->id);
    $data['user_id'] = $model?->user_id ?? $request->user()?->id;

    if ($data['status'] === 'published' && empty($data['published_at'])) {
      $data['published_at'] = now();
    }

    return $data;
  }

  protected function syncRelations(Request $request, Model $model): void
  {
    $model->tags()->sync($request->input('tags', []));
  }
}
