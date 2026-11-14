<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\TechnologyRequest;
use App\Http\Resources\TechnologyResource;
use App\Models\Technology;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;

class TechnologyController extends ApiController
{
  protected function modelClass(): string
  {
    return Technology::class;
  }

  protected function resourceClass(): string
  {
    return TechnologyResource::class;
  }

  protected function searchable(): array
  {
    return ['name', 'slug'];
  }

  protected function sortable(): array
  {
    return ['id', 'name'];
  }

  protected function indexRelations(): array
  {
    return ['portfolios'];
  }

  public function store(TechnologyRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'technologies');

    return $this->createdResponse(Technology::query()->create($data));
  }

  public function update(TechnologyRequest $request, string $id): JsonResponse
  {
    $technology = Technology::query()->findOrFail($id);
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'technologies', $technology->id);

    $technology->update($data);

    return $this->updatedResponse($technology);
  }

  public function destroy(string $id): JsonResponse
  {
    Technology::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
