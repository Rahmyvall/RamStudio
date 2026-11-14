<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends ApiController
{
  protected function modelClass(): string
  {
    return Setting::class;
  }

  protected function resourceClass(): string
  {
    return SettingResource::class;
  }

  protected function searchable(): array
  {
    return ['key', 'value', 'group'];
  }

  protected function filterable(): array
  {
    return ['group', 'type'];
  }

  protected function sortable(): array
  {
    return ['id', 'key', 'group'];
  }

  public function store(SettingRequest $request): JsonResponse
  {
    return $this->createdResponse(Setting::query()->create($request->validated()));
  }

  public function update(SettingRequest $request, string $id): JsonResponse
  {
    $setting = Setting::query()->findOrFail($id);
    $setting->update($request->validated());

    return $this->updatedResponse($setting);
  }

  public function destroy(string $id): JsonResponse
  {
    Setting::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
