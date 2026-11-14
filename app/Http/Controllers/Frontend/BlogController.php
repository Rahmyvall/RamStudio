<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends FrontendController
{
  public function index(Request $request): View
  {
    $this->boot();

    $categories = PostCategory::query()
      ->whereHas('posts', fn($q) => $q->published())
      ->orderBy('name')
      ->get();

    $activeCategory = null;
    $activeTag = null;
    $query = Post::query()->published()->with(['category', 'author']);

    if ($request->filled('kategori')) {
      $activeCategory = $categories->firstWhere('slug', $request->string('kategori')->toString());
      if ($activeCategory) {
        $query->where('post_category_id', $activeCategory->id);
      }
    }

    if ($request->filled('tag')) {
      $activeTag = Tag::query()->where('slug', $request->string('tag')->toString())->first();
      if ($activeTag) {
        $query->whereHas('tags', fn($q) => $q->where('tags.id', $activeTag->id));
      }
    }

    if ($request->filled('q')) {
      $term = $request->string('q')->toString();
      $query->where(function ($q) use ($term) {
        $q->where('title', 'like', "%{$term}%")
          ->orWhere('excerpt', 'like', "%{$term}%");
      });
    }

    $posts = $query
      ->orderByDesc('published_at')
      ->paginate(9)
      ->withQueryString();

    $popularTags = Tag::query()
      ->whereHas('posts', fn($q) => $q->published())
      ->orderBy('name')
      ->limit(12)
      ->get();

    return view('frontend.blog.index', [
      'seo' => $this->seo([
        'title' => 'Blog & Artikel — ' . $this->siteName(),
        'description' => 'Artikel, tips, dan wawasan seputar pengembangan website, sistem informasi, dan transformasi digital dari RAMStudio.',
      ]),
      'posts' => $posts,
      'categories' => $categories,
      'popularTags' => $popularTags,
      'activeCategory' => $activeCategory,
      'activeTag' => $activeTag,
      'searchTerm' => $request->string('q')->toString(),
    ]);
  }

  public function show(string $slug): View
  {
    $this->boot();

    $post = Post::query()
      ->published()
      ->with(['category', 'author', 'tags'])
      ->where('slug', $slug)
      ->firstOrFail();

    $related = Post::query()
      ->published()
      ->with('category')
      ->where('id', '!=', $post->id)
      ->when($post->post_category_id, fn($q) => $q->where('post_category_id', $post->post_category_id))
      ->orderByDesc('published_at')
      ->limit(3)
      ->get();

    if ($related->isEmpty()) {
      $related = Post::query()
        ->published()
        ->with('category')
        ->where('id', '!=', $post->id)
        ->orderByDesc('published_at')
        ->limit(3)
        ->get();
    }

    return view('frontend.blog.show', [
      'seo' => $this->seo([
        'title' => $post->title . ' — ' . $this->siteName(),
        'description' => $post->excerpt
          ?: \Illuminate\Support\Str::limit(strip_tags((string) $post->content), 155),
        'image' => \App\Support\Media::url($post->featured_image),
        'type' => 'article',
      ]),
      'post' => $post,
      'related' => $related,
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
