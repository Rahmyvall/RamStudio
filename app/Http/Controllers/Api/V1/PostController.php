<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends ApiController
{
  protected function modelClass(): string
  {
    return Post::class;
  }

  protected function resourceClass(): string
  {
    return PostResource::class;
  }

  protected function searchable(): array
  {
    return ['title', 'slug', 'excerpt'];
  }

  protected function filterable(): array
  {
    return ['post_category_id', 'status', 'user_id'];
  }

  protected function sortable(): array
  {
    return ['id', 'title', 'published_at', 'created_at'];
  }

  protected function indexRelations(): array
  {
    return ['category', 'author', 'tags'];
  }

  protected function showRelations(): array
  {
    return ['category', 'author', 'tags'];
  }

  /**
   * Public visitors only see published posts unless they are authenticated.
   */
  public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
  {
    if (! $request->user() && $request->query('status') === null) {
      $request->merge(['status' => 'published']);
    }

    return parent::index($request);
  }

  public function store(PostRequest $request): JsonResponse
  {
    $data = $request->validated();
    $tags = $data['tags'] ?? [];
    unset($data['tags']);

    $data['slug'] = Slug::unique($data['slug'] ?? $data['title'], 'posts');
    $data['user_id'] = $request->user()?->id;
    $data['featured_image'] = $request->hasFile('featured_image')
      ? $request->file('featured_image')->store('uploads/posts', 'public')
      : null;

    if ($data['status'] === 'published' && empty($data['published_at'])) {
      $data['published_at'] = now();
    }

    $post = Post::query()->create($data);
    $post->tags()->sync($tags);

    return $this->createdResponse($post->load(['category', 'author', 'tags']));
  }

  public function update(PostRequest $request, string $id): JsonResponse
  {
    $post = Post::query()->findOrFail($id);
    $data = $request->validated();
    $tags = $data['tags'] ?? null;
    unset($data['tags']);

    $data['slug'] = Slug::unique($data['slug'] ?? $data['title'], 'posts', $post->id);

    if ($request->hasFile('featured_image')) {
      $data['featured_image'] = $request->file('featured_image')->store('uploads/posts', 'public');
    } else {
      unset($data['featured_image']);
    }

    if ($data['status'] === 'published' && empty($data['published_at'])) {
      $data['published_at'] = $post->published_at ?? now();
    }

    $post->update($data);

    if ($tags !== null) {
      $post->tags()->sync($tags);
    }

    return $this->updatedResponse($post->load(['category', 'author', 'tags']));
  }

  public function destroy(string $id): JsonResponse
  {
    Post::query()->findOrFail($id)->delete();

    return $this->deletedResponse();
  }
}
