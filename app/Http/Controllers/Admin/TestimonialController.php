<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TestimonialController extends AdminCrudController
{
  protected function model(): string
  {
    return Testimonial::class;
  }

  protected function moduleTitle(): string
  {
    return 'Testimoni';
  }

  protected function routePrefix(): string
  {
    return 'admin.testimonials';
  }

  protected function searchable(): array
  {
    return ['client_name', 'company_name', 'message'];
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
      ['key' => 'client_photo', 'label' => 'Foto', 'type' => 'image'],
      ['key' => 'client_name', 'label' => 'Nama Klien'],
      ['key' => 'company_name', 'label' => 'Perusahaan'],
      ['key' => 'rating', 'label' => 'Rating', 'type' => 'rating'],
      ['key' => 'is_published', 'label' => 'Publik', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'client_name', 'label' => 'Nama Klien', 'type' => 'text', 'required' => true],
      ['name' => 'client_position', 'label' => 'Jabatan', 'type' => 'text'],
      ['name' => 'company_name', 'label' => 'Perusahaan', 'type' => 'text'],
      ['name' => 'client_photo', 'label' => 'Foto Klien', 'type' => 'image'],
      ['name' => 'message', 'label' => 'Pesan Testimoni', 'type' => 'textarea', 'rows' => 5, 'required' => true],
      ['name' => 'rating', 'label' => 'Rating (1-5)', 'type' => 'number', 'min' => 1, 'max' => 5, 'default' => 5],
      ['name' => 'is_published', 'label' => 'Publikasikan', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'client_name' => ['required', 'string', 'max:150'],
      'client_position' => ['nullable', 'string', 'max:150'],
      'company_name' => ['nullable', 'string', 'max:180'],
      'client_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
      'message' => ['required', 'string'],
      'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
      'is_published' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['is_published'] = $request->boolean('is_published');
    $data['rating'] = $data['rating'] ?? 5;

    return $data;
  }
}
