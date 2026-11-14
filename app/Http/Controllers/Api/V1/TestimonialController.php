<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\TestimonialRequest;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends ApiController
{
  protected function modelClass(): string
  {
    return Testimonial::class;
  }

  protected function resourceClass(): string
  {
    return TestimonialResource::class;
  }

  protected function searchable(): array
  {
    return ['client_name', 'company_name', 'message'];
  }

  protected function filterable(): array
  {
    return ['is_published', 'rating'];
  }

  protected function sortable(): array
  {
    return ['id', 'client_name', 'rating', 'created_at'];
  }

  public function store(TestimonialRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['client_photo'] = $request->hasFile('client_photo')
      ? $request->file('client_photo')->store('uploads/testimonials', 'public')
      : null;

    return $this->createdResponse(Testimonial::query()->create($data));
  }

  public function update(TestimonialRequest $request, string $id): JsonResponse
  {
    $testimonial = Testimonial::query()->findOrFail($id);
    $data = $request->validated();

    if ($request->hasFile('client_photo')) {
      $data['client_photo'] = $request->file('client_photo')->store('uploads/testimonials', 'public');
    } else {
      unset($data['client_photo']);
    }

    $testimonial->update($data);

    return $this->updatedResponse($testimonial);
  }

  public function destroy(string $id): JsonResponse
  {
    Testimonial::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
