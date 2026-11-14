<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PageController extends ApiController
{
  protected function modelClass(): string
  {
    return Page::class;
  }

  protected function resourceClass(): string
  {
    return PageResource::class;
  }

  protected function searchable(): array
  {
    return ['title', 'slug', 'meta_title'];
  }

  protected function filterable(): array
  {
    return ['is_published'];
  }

  protected function sortable(): array
  {
    return ['id', 'title', 'created_at'];
  }

  public function index(Request $request): AnonymousResourceCollection
  {
    if (! $request->user() && $request->query('is_published') === null) {
      $request->merge(['is_published' => '1']);
    }

    return parent::index($request);
  }

  public function store(PageRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['title'], 'pages');

    return $this->createdResponse(Page::query()->create($data));
  }

  public function update(PageRequest $request, string $id): JsonResponse
  {
    $page = Page::query()->findOrFail($id);
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['title'], 'pages', $page->id);

    $page->update($data);

    return $this->updatedResponse($page);
  }

  public function destroy(string $id): JsonResponse
  {
    Page::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
