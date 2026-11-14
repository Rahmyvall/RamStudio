<?php

namespace App\Http\Controllers\Frontend;

use App\Models\AboutJourney;
use App\Models\AboutStatistic;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\PricingPackage;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends FrontendController
{
  public function index(): View
  {
    $this->boot();

    $featuredServices = Service::query()
      ->active()
      ->with('category')
      ->orderByDesc('is_featured')
      ->orderBy('name')
      ->limit(6)
      ->get();

    $featuredPortfolios = Portfolio::query()
      ->published()
      ->with(['category', 'technologies'])
      ->orderByDesc('is_featured')
      ->orderByDesc('project_date')
      ->limit(3)
      ->get();

    $testimonials = Testimonial::query()
      ->published()
      ->orderByDesc('rating')
      ->orderBy('id')
      ->limit(12)
      ->get();

    $packages = PricingPackage::query()
      ->active()
      ->with(['features' => fn($q) => $q->orderByDesc('is_included')])
      ->orderBy('price')
      ->limit(3)
      ->get();

    $statistics = AboutStatistic::query()
      ->active()
      ->orderBy('sort_order')
      ->get();

    $journeys = AboutJourney::query()
      ->active()
      ->chronological()
      ->get();

    $categories = ServiceCategory::query()
      ->where('is_active', true)
      ->orderBy('sort_order')
      ->get();

    $latestPosts = Post::query()
      ->published()
      ->with('category')
      ->orderByDesc('published_at')
      ->limit(3)
      ->get();

    return view('frontend.home.index', [
      'seo' => $this->seo([
        'title' => ($this->siteName()) . ' — Jasa Pembuatan Website & Sistem Informasi',
        'description' => 'RAMStudio adalah studio digital yang membangun website, sistem informasi, dan aplikasi web profesional untuk instansi pemerintah, perusahaan, UMKM, dan individu.',
      ]),
      'featuredServices' => $featuredServices,
      'featuredPortfolios' => $featuredPortfolios,
      'testimonials' => $testimonials,
      'packages' => $packages,
      'statistics' => $statistics,
      'journeys' => $journeys,
      'categories' => $categories,
      'latestPosts' => $latestPosts,
      'faqs' => $this->faqs(6),
      'faqTotal' => $this->faqTotal(),
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
