<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AboutValueRequest;
use App\Http\Resources\AboutValueResource;
use App\Models\AboutValue;
use Illuminate\Http\JsonResponse;

class AboutValueController extends ApiController
{
  protected function modelClass(): string
  {
    return AboutValue::class;
  }

  protected function resourceClass(): string
  {
    return AboutValueResource::class;
  }

  protected function searchable(): array
  {
    return ['title', 'description'];
  }

  protected function filterable(): array
  {
    return ['type', 'is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'title', 'sort_order', 'created_at'];
  }

  public function store(AboutValueRequest $request): JsonResponse
  {
    return $this->createdResponse(AboutValue::query()->create($request->validated()));
  }

  public function update(AboutValueRequest $request, string $id): JsonResponse
  {
    $value = AboutValue::query()->findOrFail($id);
    $value->update($request->validated());

    return $this->updatedResponse($value);
  }

  public function destroy(string $id): JsonResponse
  {
    AboutValue::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
