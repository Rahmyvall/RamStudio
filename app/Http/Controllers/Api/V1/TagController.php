<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;

class TagController extends ApiController
{
  protected function modelClass(): string
  {
    return Tag::class;
  }

  protected function resourceClass(): string
  {
    return TagResource::class;
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

  public function store(TagRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'tags');

    return $this->createdResponse(Tag::query()->create($data));
  }

  public function update(TagRequest $request, string $id): JsonResponse
  {
    $tag = Tag::query()->findOrFail($id);
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'tags', $tag->id);

    $tag->update($data);

    return $this->updatedResponse($tag);
  }

  public function destroy(string $id): JsonResponse
  {
    Tag::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
