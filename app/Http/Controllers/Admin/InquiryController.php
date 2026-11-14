<?php

namespace App\Http\Controllers\Admin;

use App\Models\Inquiry;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InquiryController extends AdminCrudController
{
  protected function model(): string
  {
    return Inquiry::class;
  }

  protected function moduleTitle(): string
  {
    return 'Pesan Masuk';
  }

  protected function routePrefix(): string
  {
    return 'admin.inquiries';
  }

  protected function searchable(): array
  {
    return ['name', 'email', 'subject', 'company'];
  }

  protected function indexWith(): array
  {
    return ['services'];
  }

  protected function filters(): array
  {
    return [
      [
        'name' => 'status',
        'label' => 'Status',
        'options' => array_combine(Inquiry::STATUSES, array_map(
          fn($status) => ucwords(str_replace('_', ' ', $status)),
          Inquiry::STATUSES,
        )),
      ],
    ];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'name', 'label' => 'Nama'],
      ['key' => 'email', 'label' => 'Email'],
      ['key' => 'subject', 'label' => 'Subjek'],
      ['key' => 'budget', 'label' => 'Budget'],
      ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
      ['key' => 'created_at', 'label' => 'Masuk', 'type' => 'date'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
      ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
      ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
      ['name' => 'company', 'label' => 'Perusahaan', 'type' => 'text'],
      ['name' => 'subject', 'label' => 'Subjek', 'type' => 'text'],
      ['name' => 'budget', 'label' => 'Budget', 'type' => 'text'],
      ['name' => 'message', 'label' => 'Pesan', 'type' => 'textarea', 'rows' => 6, 'required' => true],
      ['name' => 'services', 'label' => 'Layanan Yang Diminati', 'type' => 'multiselect', 'options_key' => 'services'],
      ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => [
        'new' => 'Baru',
        'in_progress' => 'Diproses',
        'contacted' => 'Dihubungi',
        'closed' => 'Selesai',
      ], 'default' => 'new'],
    ];
  }

  protected function formOptions(): array
  {
    return [
      'services' => Service::query()->orderBy('name')->pluck('name', 'id')->all(),
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'name' => ['required', 'string', 'max:150'],
      'email' => ['required', 'email', 'max:190'],
      'phone' => ['nullable', 'string', 'max:40'],
      'company' => ['nullable', 'string', 'max:180'],
      'subject' => ['nullable', 'string', 'max:200'],
      'budget' => ['nullable', 'string', 'max:100'],
      'message' => ['required', 'string'],
      'services' => ['nullable', 'array'],
      'services.*' => ['integer', 'exists:services,id'],
      'status' => ['required', Rule::in(Inquiry::STATUSES)],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    unset($data['services']);

    return $data;
  }

  protected function syncRelations(Request $request, Model $model): void
  {
    $model->services()->sync($request->input('services', []));
  }
}
