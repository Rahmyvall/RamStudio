<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PricingFeatureRequest;
use App\Http\Resources\PricingFeatureResource;
use App\Models\PricingFeature;
use Illuminate\Http\JsonResponse;

class PricingFeatureController extends ApiController
{
    protected function modelClass(): string
    {
        return PricingFeature::class;
    }

    protected function resourceClass(): string
    {
        return PricingFeatureResource::class;
    }

    protected function searchable(): array
    {
        return ['feature'];
    }

    protected function filterable(): array
    {
        return ['pricing_package_id', 'is_included'];
    }

    protected function sortable(): array
    {
        return ['id', 'feature'];
    }

    protected function indexRelations(): array
    {
        return ['package'];
    }

    public function store(PricingFeatureRequest $request): JsonResponse
    {
        return $this->createdResponse(PricingFeature::query()->create($request->validated()));
    }

    public function update(PricingFeatureRequest $request, string $id): JsonResponse
    {
        $feature = PricingFeature::query()->findOrFail($id);
        $feature->update($request->validated());

        return $this->updatedResponse($feature);
    }

    public function destroy(string $id): JsonResponse
    {
        PricingFeature::query()->findOrFail($id)->delete();

        return $this->deletedResponse();
    }
}
