<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PricingPackageRequest;
use App\Http\Resources\PricingPackageResource;
use App\Models\PricingPackage;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;

class PricingPackageController extends ApiController
{
  protected function modelClass(): string
  {
    return PricingPackage::class;
  }

  protected function resourceClass(): string
  {
    return PricingPackageResource::class;
  }

  protected function searchable(): array
  {
    return ['name', 'slug'];
  }

  protected function filterable(): array
  {
    return ['is_popular', 'is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'name', 'price', 'created_at'];
  }

  protected function indexRelations(): array
  {
    return ['features'];
  }

  protected function showRelations(): array
  {
    return ['features'];
  }

  public function store(PricingPackageRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'pricing_packages');

    return $this->createdResponse(PricingPackage::query()->create($data));
  }

  public function update(PricingPackageRequest $request, string $id): JsonResponse
  {
    $package = PricingPackage::query()->findOrFail($id);
    $data = $request->validated();
    $data['slug'] = Slug::unique($data['slug'] ?? $data['name'], 'pricing_packages', $package->id);

    $package->update($data);

    return $this->updatedResponse($package);
  }

  public function destroy(string $id): JsonResponse
  {
    PricingPackage::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
