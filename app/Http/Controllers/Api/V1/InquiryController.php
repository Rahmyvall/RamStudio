<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\InquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;

class InquiryController extends ApiController
{
  protected function modelClass(): string
  {
    return Inquiry::class;
  }

  protected function resourceClass(): string
  {
    return InquiryResource::class;
  }

  protected function searchable(): array
  {
    return ['name', 'email', 'company', 'subject'];
  }

  protected function filterable(): array
  {
    return ['status'];
  }

  protected function sortable(): array
  {
    return ['id', 'name', 'created_at'];
  }

  protected function indexRelations(): array
  {
    return ['services'];
  }

  protected function showRelations(): array
  {
    return ['services'];
  }

  /**
   * Public endpoint: accept a new inquiry from the website contact form.
   */
  public function store(InquiryRequest $request): JsonResponse
  {
    $data = $request->validated();
    $services = $data['services'] ?? [];
    unset($data['services']);

    $data['status'] = 'new';

    $inquiry = Inquiry::query()->create($data);
    $inquiry->services()->sync($services);

    return $this->createdResponse($inquiry->load('services'), 'Pesan Anda berhasil dikirim.');
  }

  public function update(InquiryRequest $request, string $id): JsonResponse
  {
    $inquiry = Inquiry::query()->findOrFail($id);
    $data = $request->validated();
    $services = $data['services'] ?? null;
    unset($data['services']);

    $inquiry->update($data);

    if ($services !== null) {
      $inquiry->services()->sync($services);
    }

    return $this->updatedResponse($inquiry->load('services'));
  }

  public function destroy(string $id): JsonResponse
  {
    Inquiry::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
