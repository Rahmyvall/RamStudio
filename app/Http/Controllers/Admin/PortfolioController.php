<?php

namespace App\Http\Controllers\Admin;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\Technology;
use App\Support\Media;
use App\Support\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PortfolioController extends AdminCrudController
{
  protected function model(): string
  {
    return Portfolio::class;
  }

  protected function moduleTitle(): string
  {
    return 'Portofolio';
  }

  protected function routePrefix(): string
  {
    return 'admin.portfolios';
  }

  protected function searchable(): array
  {
    return ['title', 'slug', 'client_name'];
  }

  protected function indexWith(): array
  {
    return ['category', 'technologies', 'images'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'portfolio_category_id',
        'label' => 'Kategori',
        'options' => PortfolioCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
      ],
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
      ['key' => 'thumbnail', 'label' => 'Thumbnail', 'type' => 'image'],
      ['key' => 'title', 'label' => 'Judul'],
      ['key' => 'category.name', 'label' => 'Kategori', 'type' => 'relation'],
      ['key' => 'technologies', 'label' => 'Teknologi', 'type' => 'tags'],
      ['key' => 'images', 'label' => 'Galeri', 'type' => 'gallery'],
      ['key' => 'client_name', 'label' => 'Klien'],
      ['key' => 'project_date', 'label' => 'Tanggal', 'type' => 'date'],
      ['key' => 'is_published', 'label' => 'Publik', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'portfolio_category_id', 'label' => 'Kategori', 'type' => 'select', 'options_key' => 'categories'],
      ['name' => 'title', 'label' => 'Judul Proyek', 'type' => 'text', 'required' => true],
      ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'help' => 'Kosongkan untuk membuat otomatis.'],
      ['name' => 'client_name', 'label' => 'Nama Klien', 'type' => 'text'],
      ['name' => 'project_url', 'label' => 'URL Proyek', 'type' => 'url'],
      ['name' => 'project_date', 'label' => 'Tanggal Proyek', 'type' => 'date'],
      ['name' => 'thumbnail', 'label' => 'Thumbnail', 'type' => 'image'],
      ['name' => 'gallery', 'label' => 'Galeri Foto', 'type' => 'images', 'help' => 'Pilih beberapa foto sekaligus. Foto pertama otomatis dipakai sebagai thumbnail bila thumbnail belum diisi.'],
      ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 6],
      ['name' => 'technologies', 'label' => 'Teknologi', 'type' => 'checkboxes', 'options_key' => 'technologies', 'help' => 'Centang satu atau lebih teknologi yang dipakai proyek ini.'],
      ['name' => 'is_featured', 'label' => 'Unggulan', 'type' => 'checkbox'],
      ['name' => 'is_published', 'label' => 'Publikasikan', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function formOptions(): array
  {
    return [
      'categories' => PortfolioCategory::query()->orderBy('name')->pluck('name', 'id')->all(),
      'technologies' => Technology::query()->orderBy('name')->pluck('name', 'id')->all(),
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'portfolio_category_id' => ['nullable', 'integer', 'exists:portfolio_categories,id'],
      'title' => ['required', 'string', 'max:180'],
      'slug' => ['nullable', 'string', 'max:200', Rule::unique('portfolios', 'slug')->ignore($model?->id)],
      'client_name' => ['nullable', 'string', 'max:180'],
      'project_url' => ['nullable', 'url', 'max:255'],
      'project_date' => ['nullable', 'date'],
      'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'gallery' => ['nullable', 'array'],
      'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
      'remove_images' => ['nullable', 'array'],
      'remove_images.*' => ['integer', 'exists:portfolio_images,id'],
      'description' => ['nullable', 'string'],
      'technologies' => ['nullable', 'array'],
      'technologies.*' => ['integer', 'exists:technologies,id'],
      'is_featured' => ['nullable', 'boolean'],
      'is_published' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    unset($data['technologies'], $data['gallery'], $data['remove_images']);

    $data['slug'] = Slug::unique(($data['slug'] ?? null) ?: $data['title'], 'portfolios', $model?->id);
    $data['is_featured'] = $request->boolean('is_featured');
    $data['is_published'] = $request->boolean('is_published');

    return $data;
  }

  protected function syncRelations(Request $request, Model $model): void
  {
    $model->technologies()->sync($request->input('technologies', []));

    $this->syncGallery($request, $model);
  }

  /**
   * Persist the uploaded gallery photos and delete the ones the admin removed.
   */
  private function syncGallery(Request $request, Model $model): void
  {
    /** @var \App\Models\Portfolio $model */
    $removed = array_map('intval', (array) $request->input('remove_images', []));

    if ($removed !== []) {
      $images = $model->images()->whereIn('id', $removed)->get();

      foreach ($images as $image) {
        Media::delete($image->image_path);
        $image->delete();
      }
    }

    $files = $request->file('gallery', []);
    $files = is_array($files) ? $files : [$files];

    foreach ($files as $file) {
      if (! $file || ! $file->isValid()) {
        continue;
      }

      $model->images()->create([
        'image_path' => $file->store('uploads/portfolio-images', 'public'),
        'alt_text' => $model->title,
      ]);
    }

    // Fall back to the first gallery photo when no thumbnail was uploaded.
    if (! $model->thumbnail) {
      $first = $model->images()->orderBy('id')->first();

      if ($first) {
        $model->forceFill(['thumbnail' => $first->image_path])->save();
      }
    }
  }

  protected function formData(?Model $model): array
  {
    return [
      'galleryImages' => $model
        ? $model->images()->orderBy('id')->get()
        : collect(),
    ];
  }
}
