<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PostCategoryRequest;
use App\Http\Resources\PostCategoryResource;
use App\Models\PostCategory;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;

class PostCategoryController extends ApiController
{
  protected function modelClass(): string
  {
    return PostCategory::class;
  }

  protected function resourceClass(): string
  {
    return PostCategoryResource::class;
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
    return ['posts'];
  }

  public function store(PostCategoryRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'post_categories');

    return $this->createdResponse(PostCategory::query()->create($data));
  }

  public function update(PostCategoryRequest $request, string $id): JsonResponse
  {
    $category = PostCategory::query()->findOrFail($id);
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'post_categories', $category->id);

    $category->update($data);

    return $this->updatedResponse($category);
  }

  public function destroy(string $id): JsonResponse
  {
    PostCategory::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
