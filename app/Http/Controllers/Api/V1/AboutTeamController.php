<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AboutPersonRequest;
use App\Http\Resources\AboutTeamResource;
use App\Models\AboutTeam;
use App\Support\Media;
use Illuminate\Http\JsonResponse;

/**
 * Public read + admin write endpoints for the About team members.
 */
class AboutTeamController extends ApiController
{
    protected function modelClass(): string
    {
        return AboutTeam::class;
    }

    protected function resourceClass(): string
    {
        return AboutTeamResource::class;
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

        return $this->createdResponse(AboutTeam::query()->create($data));
    }

    public function update(AboutPersonRequest $request, string $id): JsonResponse
    {
        $member = AboutTeam::query()->findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            Media::delete($member->photo);
            $data['photo'] = $request->file('photo')->store('uploads/about', 'public');
        } else {
            unset($data['photo']);
        }

        $member->update($data);

        return $this->updatedResponse($member);
    }

    public function destroy(string $id): JsonResponse
    {
        AboutTeam::query()->findOrFail($id)->delete();

        return $this->deletedResponse();
    }
}
