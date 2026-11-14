<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ServiceCategoryRequest;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\ServiceCategory;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;

class ServiceCategoryController extends ApiController
{
    protected function modelClass(): string
    {
        return ServiceCategory::class;
    }

    protected function resourceClass(): string
    {
        return ServiceCategoryResource::class;
    }

    protected function searchable(): array
    {
        return ['name', 'slug'];
    }

    protected function filterable(): array
    {
        return ['is_active'];
    }

    protected function sortable(): array
    {
        return ['id', 'name', 'sort_order', 'created_at'];
    }

    protected function indexRelations(): array
    {
        return ['services'];
    }

    protected function showRelations(): array
    {
        return ['services'];
    }

    public function store(ServiceCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'service_categories');

        $category = ServiceCategory::query()->create($data);

        return $this->createdResponse($category);
    }

    public function update(ServiceCategoryRequest $request, string $id): JsonResponse
    {
        $category = ServiceCategory::query()->findOrFail($id);
        $data = $request->validated();
        $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'service_categories', $category->id);

        $category->update($data);

        return $this->updatedResponse($category);
    }

    public function destroy(string $id): JsonResponse
    {
        ServiceCategory::query()->findOrFail($id)->delete();

        return $this->deletedResponse();
    }
}
