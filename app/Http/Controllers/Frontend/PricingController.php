<?php

namespace App\Http\Controllers\Frontend;

use App\Models\PricingPackage;
use Illuminate\Contracts\View\View;

class PricingController extends FrontendController
{
  public function index(): View
  {
    $this->boot();

    $packages = PricingPackage::query()
      ->active()
      ->with(['features' => fn($q) => $q->orderByDesc('is_included')->orderBy('id')])
      ->orderBy('price')
      ->get();

    return view('frontend.pricing.index', [
      'seo' => $this->seo([
        'title' => 'Paket Harga — ' . $this->siteName(),
        'description' => 'Pilih paket pembuatan website dan sistem informasi yang sesuai kebutuhan Anda. Transparan, fleksibel, dan dapat disesuaikan.',
      ]),
      'packages' => $packages,
      'faqs' => $this->faqs(6),
      'faqTotal' => $this->faqTotal(),
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
