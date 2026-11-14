<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends FrontendController
{
  public function show(string $slug): View
  {
    $this->boot();

    $page = Page::query()
      ->published()
      ->where('slug', $slug)
      ->firstOrFail();

    return view('frontend.pages.show', [
      'seo' => $this->seo([
        'title' => ($page->meta_title ?: $page->title) . ' — ' . $this->siteName(),
        'description' => $page->meta_description
          ?: \Illuminate\Support\Str::limit(strip_tags((string) $page->content), 155),
      ]),
      'page' => $page,
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
