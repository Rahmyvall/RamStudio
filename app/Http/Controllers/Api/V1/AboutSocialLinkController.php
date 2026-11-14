<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AboutSocialLinkRequest;
use App\Http\Resources\AboutSocialLinkResource;
use App\Models\AboutSocialLink;
use Illuminate\Http\JsonResponse;

class AboutSocialLinkController extends ApiController
{
  protected function modelClass(): string
  {
    return AboutSocialLink::class;
  }

  protected function resourceClass(): string
  {
    return AboutSocialLinkResource::class;
  }

  protected function searchable(): array
  {
    return ['platform', 'label', 'url'];
  }

  protected function filterable(): array
  {
    return ['is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'platform', 'sort_order', 'created_at'];
  }

  public function store(AboutSocialLinkRequest $request): JsonResponse
  {
    return $this->createdResponse(AboutSocialLink::query()->create($request->validated()));
  }

  public function update(AboutSocialLinkRequest $request, string $id): JsonResponse
  {
    $link = AboutSocialLink::query()->findOrFail($id);
    $link->update($request->validated());

    return $this->updatedResponse($link);
  }

  public function destroy(string $id): JsonResponse
  {
    AboutSocialLink::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
