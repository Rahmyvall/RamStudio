<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends AdminCrudController
{
  protected function model(): string
  {
    return Setting::class;
  }

  protected function moduleTitle(): string
  {
    return 'Pengaturan';
  }

  protected function routePrefix(): string
  {
    return 'admin.settings';
  }

  protected function searchable(): array
  {
    return ['key', 'value', 'group'];
  }

  protected function columns(): array
  {
    return [
      ['key' => 'key', 'label' => 'Key', 'type' => 'code'],
      ['key' => 'group', 'label' => 'Grup'],
      ['key' => 'type', 'label' => 'Tipe'],
      ['key' => 'value', 'label' => 'Nilai', 'type' => 'truncate'],
      ['key' => 'updated_at', 'label' => 'Diperbarui', 'type' => 'date'],
    ];
  }

  protected function fields(): array
  {
    return [
      ['name' => 'key', 'label' => 'Key', 'type' => 'text', 'required' => true, 'help' => 'Contoh: site_name'],
      ['name' => 'value', 'label' => 'Nilai', 'type' => 'textarea', 'rows' => 4],
      ['name' => 'group', 'label' => 'Grup', 'type' => 'text', 'default' => 'general'],
      ['name' => 'type', 'label' => 'Tipe', 'type' => 'select', 'options' => [
        'text' => 'Text',
        'textarea' => 'Textarea',
        'image' => 'Image',
        'boolean' => 'Boolean',
        'json' => 'JSON',
      ], 'default' => 'text'],
    ];
  }

  protected function rules(Request $request, ?Model $model): array
  {
    return [
      'key' => ['required', 'string', 'max:150', Rule::unique('settings', 'key')->ignore($model?->id)],
      'value' => ['nullable', 'string'],
      'group' => ['nullable', 'string', 'max:100'],
      'type' => ['nullable', Rule::in(['text', 'textarea', 'image', 'boolean', 'json'])],
    ];
  }

  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    $data['group'] = $data['group'] ?? 'general';
    $data['type'] = $data['type'] ?? 'text';

    return $data;
  }
}
