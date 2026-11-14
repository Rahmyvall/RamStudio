<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PortfolioCategoryRequest;
use App\Http\Resources\PortfolioCategoryResource;
use App\Models\PortfolioCategory;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;

class PortfolioCategoryController extends ApiController
{
  protected function modelClass(): string
  {
    return PortfolioCategory::class;
  }

  protected function resourceClass(): string
  {
    return PortfolioCategoryResource::class;
  }

  protected function searchable(): array
  {
    return ['name', 'slug'];
  }

  protected function sortable(): array
  {
    return ['id', 'name', 'created_at'];
  }

  protected function indexRelations(): array
  {
    return ['portfolios'];
  }

  protected function showRelations(): array
  {
    return ['portfolios'];
  }

  public function store(PortfolioCategoryRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'portfolio_categories');

    return $this->createdResponse(PortfolioCategory::query()->create($data));
  }

  public function update(PortfolioCategoryRequest $request, string $id): JsonResponse
  {
    $category = PortfolioCategory::query()->findOrFail($id);
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'portfolio_categories', $category->id);

    $category->update($data);

    return $this->updatedResponse($category);
  }

  public function destroy(string $id): JsonResponse
  {
    PortfolioCategory::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
