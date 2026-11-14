<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PortfolioImageRequest;
use App\Http\Resources\PortfolioImageResource;
use App\Models\PortfolioImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioImageController extends ApiController
{
  protected function modelClass(): string
  {
    return PortfolioImage::class;
  }

  protected function resourceClass(): string
  {
    return PortfolioImageResource::class;
  }

  protected function filterable(): array
  {
    return ['portfolio_id'];
  }

  protected function indexRelations(): array
  {
    return ['portfolio'];
  }

  public function store(PortfolioImageRequest $request): JsonResponse
  {
    $data = $request->validated();
    $data['image_path'] = $request->file('image_path')->store('uploads/portfolio-images', 'public');

    return $this->createdResponse(PortfolioImage::query()->create($data));
  }

  public function update(PortfolioImageRequest $request, string $id): JsonResponse
  {
    $image = PortfolioImage::query()->findOrFail($id);
    $data = $request->validated();

    if ($request->hasFile('image_path')) {
      $data['image_path'] = $request->file('image_path')->store('uploads/portfolio-images', 'public');
    } else {
      unset($data['image_path']);
    }

    $image->update($data);

    return $this->updatedResponse($image);
  }

  public function destroy(string $id): JsonResponse
  {
    PortfolioImage::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
