<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AboutJourneyRequest;
use App\Http\Resources\AboutJourneyResource;
use App\Models\AboutJourney;
use Illuminate\Http\JsonResponse;

class AboutJourneyController extends ApiController
{
  protected function modelClass(): string
  {
    return AboutJourney::class;
  }

  protected function resourceClass(): string
  {
    return AboutJourneyResource::class;
  }

  protected function searchable(): array
  {
    return ['title', 'description'];
  }

  protected function filterable(): array
  {
    return ['year', 'is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'year', 'sort_order', 'created_at'];
  }

  public function store(AboutJourneyRequest $request): JsonResponse
  {
    return $this->createdResponse(AboutJourney::query()->create($request->validated()));
  }

  public function update(AboutJourneyRequest $request, string $id): JsonResponse
  {
    $journey = AboutJourney::query()->findOrFail($id);
    $journey->update($request->validated());

    return $this->updatedResponse($journey);
  }

  public function destroy(string $id): JsonResponse
  {
    AboutJourney::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
