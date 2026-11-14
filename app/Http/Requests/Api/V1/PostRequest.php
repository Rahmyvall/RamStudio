<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;
use App\Models\Post;
use Illuminate\Validation\Rule;

class PostRequest extends ApiFormRequest
{
  public function rules(): array
  {
    $id = $this->boundId('post');

    return [
      'post_category_id' => ['nullable', 'integer', 'exists:post_categories,id'],
      'title' => ['required', 'string', 'max:220'],
      'slug' => ['nullable', 'string', 'max:240', Rule::unique('posts', 'slug')->ignore($id)],
      'excerpt' => ['nullable', 'string', 'max:500'],
      'content' => ['required', 'string'],
      'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
      'tags' => ['nullable', 'array'],
      'tags.*' => ['integer', 'exists:tags,id'],
      'status' => ['required', Rule::in(Post::STATUSES)],
      'published_at' => ['nullable', 'date'],
    ];
  }
}
