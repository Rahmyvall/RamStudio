<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\Technology;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortfolioController extends FrontendController
{
  public function index(Request $request): View
  {
    $this->boot();

    $categories = PortfolioCategory::query()
      ->whereHas('portfolios', fn($q) => $q->published())
      ->orderBy('name')
      ->get();

    $technologies = Technology::query()
      ->whereHas('portfolios', fn($q) => $q->published())
      ->orderBy('name')
      ->get(['id', 'name', 'slug']);

    $activeCategory = null;
    $activeTechnology = null;
    $search = trim($request->string('q')->toString());

    $query = Portfolio::query()->published()->with(['category', 'technologies']);

    if ($request->filled('kategori')) {
      $activeCategory = $categories->firstWhere('slug', $request->string('kategori')->toString());
      if ($activeCategory) {
        $query->where('portfolio_category_id', $activeCategory->id);
      }
    }

    if ($request->filled('teknologi')) {
      $activeTechnology = $technologies->firstWhere('slug', $request->string('teknologi')->toString());
      if ($activeTechnology) {
        $query->whereHas('technologies', fn($q) => $q->where('technologies.id', $activeTechnology->id));
      }
    }

    if ($search !== '') {
      $term = '%' . $search . '%';
      $query->where(function ($q) use ($term) {
        $q->where('title', 'like', $term)
          ->orWhere('client_name', 'like', $term)
          ->orWhere('description', 'like', $term);
      });
    }

    // Totals are computed before pagination so the summary strip stays stable.
    $totalProjects = Portfolio::query()->published()->count();
    $totalClients = Portfolio::query()->published()->whereNotNull('client_name')->distinct()->count('client_name');

    $portfolios = $query
      ->orderByDesc('is_featured')
      ->orderByDesc('project_date')
      ->paginate(9)
      ->withQueryString();

    return view('frontend.portfolios.index', [
      'seo' => $this->seo([
        'title' => 'Portofolio — ' . $this->siteName(),
        'description' => 'Galeri proyek RAMStudio: website company profile, sistem informasi, aplikasi web, dan dashboard administrasi untuk berbagai klien.',
      ]),
      'categories' => $categories,
      'technologies' => $technologies,
      'portfolios' => $portfolios,
      'activeCategory' => $activeCategory,
      'activeTechnology' => $activeTechnology,
      'search' => $search,
      'totalProjects' => $totalProjects,
      'totalClients' => $totalClients,
    ]);
  }

  public function show(string $slug): View
  {
    $this->boot();

    $portfolio = Portfolio::query()
      ->published()
      ->with(['category', 'images', 'technologies'])
      ->where('slug', $slug)
      ->firstOrFail();

    $related = Portfolio::query()
      ->published()
      ->with(['category', 'technologies'])
      ->where('id', '!=', $portfolio->id)
      ->when($portfolio->portfolio_category_id, fn($q) => $q->where('portfolio_category_id', $portfolio->portfolio_category_id))
      ->orderByDesc('is_featured')
      ->limit(3)
      ->get();

    if ($related->isEmpty()) {
      $related = Portfolio::query()
        ->published()
        ->with(['category', 'technologies'])
        ->where('id', '!=', $portfolio->id)
        ->orderByDesc('project_date')
        ->limit(3)
        ->get();
    }

    return view('frontend.portfolios.show', [
      'seo' => $this->seo([
        'title' => $portfolio->title . ' — Portofolio ' . $this->siteName(),
        'description' => \Illuminate\Support\Str::limit(strip_tags((string) $portfolio->description), 155),
        'image' => \App\Support\Media::url($portfolio->thumbnail),
        'type' => 'article',
      ]),
      'portfolio' => $portfolio,
      'related' => $related,
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
