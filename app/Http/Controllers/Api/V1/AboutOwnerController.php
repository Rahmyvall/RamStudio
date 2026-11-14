<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AboutPersonRequest;
use App\Http\Resources\AboutOwnerResource;
use App\Models\AboutOwner;
use App\Support\Media;
use Illuminate\Http\JsonResponse;

class AboutOwnerController extends ApiController
{
  protected function modelClass(): string
  {
    return AboutOwner::class;
  }

  protected function resourceClass(): string
  {
    return AboutOwnerResource::class;
  }

  protected function searchable(): array
  {
    return ['name', 'position', 'email'];
  }

  protected function filterable(): array
  {
    return ['is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'name', 'sort_order', 'created_at'];
  }

  public function store(AboutPersonRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['photo'] = $request->hasFile('photo')
      ? $request->file('photo')->store('uploads/about', 'public')
      : null;

    return $this->createdResponse(AboutOwner::query()->create($data));
  }

  public function update(AboutPersonRequest $request, string $id): JsonResponse
  {
    $owner = AboutOwner::query()->findOrFail($id);
    $data = $request->validated();

    if ($request->hasFile('photo')) {
      Media::delete($owner->photo);
      $data['photo'] = $request->file('photo')->store('uploads/about', 'public');
    } else {
      unset($data['photo']);
    }

    $owner->update($data);

    return $this->updatedResponse($owner);
  }

  public function destroy(string $id): JsonResponse
  {
    AboutOwner::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
