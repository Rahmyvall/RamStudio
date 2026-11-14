<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AboutStatisticRequest;
use App\Http\Resources\AboutStatisticResource;
use App\Models\AboutStatistic;
use Illuminate\Http\JsonResponse;

class AboutStatisticController extends ApiController
{
  protected function modelClass(): string
  {
    return AboutStatistic::class;
  }

  protected function resourceClass(): string
  {
    return AboutStatisticResource::class;
  }

  protected function searchable(): array
  {
    return ['label', 'value'];
  }

  protected function filterable(): array
  {
    return ['is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'label', 'sort_order', 'created_at'];
  }

  public function store(AboutStatisticRequest $request): JsonResponse
  {
    return $this->createdResponse(AboutStatistic::query()->create($request->validated()));
  }

  public function update(AboutStatisticRequest $request, string $id): JsonResponse
  {
    $statistic = AboutStatistic::query()->findOrFail($id);
    $statistic->update($request->validated());

    return $this->updatedResponse($statistic);
  }

  public function destroy(string $id): JsonResponse
  {
    AboutStatistic::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
