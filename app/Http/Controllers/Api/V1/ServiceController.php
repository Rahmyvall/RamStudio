<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends ApiController
{
  protected function modelClass(): string
  {
    return Service::class;
  }

  protected function resourceClass(): string
  {
    return ServiceResource::class;
  }

  protected function searchable(): array
  {
    return ['name', 'slug', 'short_description'];
  }

  protected function filterable(): array
  {
    return ['service_category_id', 'is_featured', 'is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'name', 'starting_price', 'created_at'];
  }

  protected function indexRelations(): array
  {
    return ['category'];
  }

  protected function showRelations(): array
  {
    return ['category', 'inquiries'];
  }

  public function store(ServiceRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'services');
    $data['image'] = $request->hasFile('image')
      ? $request->file('image')->store('uploads/services', 'public')
      : null;

    $service = Service::query()->create($data);

    return $this->createdResponse($service->load('category'));
  }

  public function update(ServiceRequest $request, string $id): JsonResponse
  {
    $service = Service::query()->findOrFail($id);
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'services', $service->id);

    if ($request->hasFile('image')) {
      $data['image'] = $request->file('image')->store('uploads/services', 'public');
    } else {
      unset($data['image']);
    }

    $service->update($data);

    return $this->updatedResponse($service->load('category'));
  }

  public function destroy(string $id): JsonResponse
  {
    Service::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
