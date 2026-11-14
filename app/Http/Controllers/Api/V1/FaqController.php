<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class FaqController extends ApiController
{
  protected function modelClass(): string
  {
    return Faq::class;
  }

  protected function resourceClass(): string
  {
    return FaqResource::class;
  }

  protected function searchable(): array
  {
    return ['question', 'answer'];
  }

  protected function filterable(): array
  {
    return ['is_active'];
  }

  protected function sortable(): array
  {
    return ['id', 'sort_order', 'created_at'];
  }

  public function store(FaqRequest $request): JsonResponse
  {
    return $this->createdResponse(Faq::query()->create($request->validated()));
  }

  public function update(FaqRequest $request, string $id): JsonResponse
  {
    $faq = Faq::query()->findOrFail($id);
    $faq->update($request->validated());

    return $this->updatedResponse($faq);
  }

  public function destroy(string $id): JsonResponse
  {
    Faq::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
