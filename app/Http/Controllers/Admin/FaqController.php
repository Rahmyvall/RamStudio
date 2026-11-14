<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FaqController extends AdminCrudController
{
  protected function model(): string
  {
    return Faq::class;
  }

  protected function moduleTitle(): string
  {
    return 'FAQ';
  }

  protected function routePrefix(): string
  {
    return 'admin.faqs';
  }

  protected function searchable(): array
  {
    return ['question', 'answer'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'question', 'label' => 'Pertanyaan'],
      ['key' => 'sort_order', 'label' => 'Urutan'],
      ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'question', 'label' => 'Pertanyaan', 'type' => 'textarea', 'rows' => 2, 'required' => true],
      ['name' => 'answer', 'label' => 'Jawaban', 'type' => 'textarea', 'rows' => 6, 'required' => true],
      ['name' => 'sort_order', 'label' => 'Urutan Tampil', 'type' => 'number', 'default' => 0],
      ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'question' => ['required', 'string', 'max:500'],
      'answer' => ['required', 'string'],
      'sort_order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['is_active'] = $request->boolean('is_active');
    $data['sort_order'] = $data['sort_order'] ?? 0;

    return $data;
  }
}
