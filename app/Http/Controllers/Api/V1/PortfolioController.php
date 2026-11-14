<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PortfolioRequest;
use App\Http\Resources\PortfolioResource;
use App\Models\Portfolio;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;

class PortfolioController extends ApiController
{
  protected function modelClass(): string
  {
    return Portfolio::class;
  }

  protected function resourceClass(): string
  {
    return PortfolioResource::class;
  }

  protected function searchable(): array
  {
    return ['title', 'slug', 'client_name'];
  }

  protected function filterable(): array
  {
    return ['portfolio_category_id', 'is_featured', 'is_published'];
  }

  protected function sortable(): array
  {
    return ['id', 'title', 'project_date', 'created_at'];
  }

  protected function indexRelations(): array
  {
    return ['category', 'technologies'];
  }

  protected function showRelations(): array
  {
    return ['category', 'images', 'technologies'];
  }

  public function store(PortfolioRequest $request): JsonResponse
  {
    $data = $request->validated();
    $technologies = $data['technologies'] ?? [];
    unset($data['technologies']);

    $data['slug'] = Slug::unique($data['slug'] ?? $data['title'], 'portfolios');
    $data['thumbnail'] = $request->hasFile('thumbnail')
      ? $request->file('thumbnail')->store('uploads/portfolios', 'public')
      : null;

    $portfolio = Portfolio::query()->create($data);
    $portfolio->technologies()->sync($technologies);

    return $this->createdResponse($portfolio->load(['category', 'technologies']));
  }

  public function update(PortfolioRequest $request, string $id): JsonResponse
  {
    $portfolio = Portfolio::query()->findOrFail($id);
    $data = $request->validated();
    $technologies = $data['technologies'] ?? null;
    unset($data['technologies']);

    $data['slug'] = Slug::unique($data['slug'] ?? $data['title'], 'portfolios', $portfolio->id);

    if ($request->hasFile('thumbnail')) {
      $data['thumbnail'] = $request->file('thumbnail')->store('uploads/portfolios', 'public');
    } else {
      unset($data['thumbnail']);
    }

    $portfolio->update($data);

    if ($technologies !== null) {
      $portfolio->technologies()->sync($technologies);
    }

    return $this->updatedResponse($portfolio->load(['category', 'images', 'technologies']));
  }

  public function destroy(string $id): JsonResponse
  {
    Portfolio::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
