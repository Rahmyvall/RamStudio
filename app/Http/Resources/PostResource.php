<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'post_category_id' => $this->post_category_id,
      'user_id' => $this->user_id,
      'title' => $this->title,
      'slug' => $this->slug,
      'excerpt' => $this->excerpt,
      'content' => $this->content,
      'featured_image' => \App\Support\Media::url($this->featured_image),
      'status' => $this->status,
      'published_at' => $this->published_at?->toIso8601String(),
      'category' => new PostCategoryResource($this->whenLoaded('category')),
      'author' => $this->whenLoaded('author', fn() => [
        'id' => $this->author->id,
        'name' => $this->author->name,
      ]),
      'tags' => TagResource::collection($this->whenLoaded('tags')),
      'created_at' => $this->created_at?->toIso8601String(),
      'updated_at' => $this->updated_at?->toIso8601String(),
    ];
  }
}
