<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutSocialLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AboutSocialLinkController extends AdminCrudController
{
    protected function model(): string
    {
        return AboutSocialLink::class;
    }

    protected function moduleTitle(): string
    {
        return 'Media Sosial';
    }

    protected function routePrefix(): string
    {
        return 'admin.about.social-links';
    }

    protected function searchable(): array
    {
        return ['platform', 'label', 'url'];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'platform', 'label' => 'Platform'],
            ['key' => 'label', 'label' => 'Label'],
            ['key' => 'url', 'label' => 'URL', 'type' => 'truncate'],
            ['key' => 'icon', 'label' => 'Ikon', 'type' => 'code'],
            ['key' => 'sort_order', 'label' => 'Urutan'],
            ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'bool'],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'platform', 'label' => 'Platform', 'type' => 'text', 'required' => true, 'help' => 'Contoh: Instagram'],
            ['name' => 'label', 'label' => 'Label', 'type' => 'text', 'help' => 'Contoh: @ramstudio'],
            ['name' => 'url', 'label' => 'URL', 'type' => 'url', 'required' => true],
            ['name' => 'icon', 'label' => 'Ikon (Font Awesome)', 'type' => 'text', 'help' => 'Contoh: fa fa-instagram'],
            ['name' => 'sort_order', 'label' => 'Urutan Tampil', 'type' => 'number', 'default' => 0],
            ['name' => 'is_active', 'label' => 'Aktif', 'type' => 'checkbox', 'default' => true],
        ];
    }

    protected function rules(Request $request, ?Model $model): array
    {
        return [
            'platform' => ['required', 'string', 'max:100'],
            'label' => ['nullable', 'string', 'max:150'],
            'url' => ['required', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareData(Request $request, array $data, ?Model $model): array
    {
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
