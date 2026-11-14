<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServiceController extends FrontendController
{
  public function index(Request $request): View
  {
    $this->boot();

    $categories = ServiceCategory::query()
      ->where('is_active', true)
      ->orderBy('sort_order')
      ->get();

    $activeCategory = null;
    $query = Service::query()->active()->with('category');

    if ($request->filled('kategori')) {
      $activeCategory = $categories->firstWhere('slug', $request->string('kategori')->toString());
      if ($activeCategory) {
        $query->where('service_category_id', $activeCategory->id);
      }
    }

    $services = $query->orderByDesc('is_featured')->orderBy('name')->get();

    return view('frontend.services.index', [
      'seo' => $this->seo([
        'title' => 'Layanan — ' . $this->siteName(),
        'description' => 'Layanan RAMStudio: pembuatan website, pengembangan sistem informasi, aplikasi web, dashboard administrasi, dan konsultasi teknologi informasi.',
      ]),
      'categories' => $categories,
      'services' => $services,
      'activeCategory' => $activeCategory,
    ]);
  }

  public function show(string $slug): View
  {
    $this->boot();

    $service = Service::query()
      ->active()
      ->with('category')
      ->where('slug', $slug)
      ->firstOrFail();

    $related = Service::query()
      ->active()
      ->where('id', '!=', $service->id)
      ->when($service->service_category_id, fn($q) => $q->where('service_category_id', $service->service_category_id))
      ->orderByDesc('is_featured')
      ->limit(3)
      ->get();

    if ($related->isEmpty()) {
      $related = Service::query()
        ->active()
        ->where('id', '!=', $service->id)
        ->orderByDesc('is_featured')
        ->limit(3)
        ->get();
    }

    return view('frontend.services.show', [
      'seo' => $this->seo([
        'title' => $service->name . ' — ' . $this->siteName(),
        'description' => $service->short_description
          ?: \Illuminate\Support\Str::limit(strip_tags((string) $service->description), 155),
        'image' => \App\Support\Media::url($service->image),
        'type' => 'article',
      ]),
      'service' => $service,
      'related' => $related,
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
