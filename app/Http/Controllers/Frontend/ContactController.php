<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Service;
use Illuminate\Contracts\View\View;

class ContactController extends FrontendController
{
  public function index(): View
  {
    $this->boot();

    $services = Service::query()
      ->active()
      ->orderBy('name')
      ->get(['id', 'name', 'slug']);

    return view('frontend.contact.index', [
      'seo' => $this->seo([
        'title' => 'Kontak & Konsultasi — ' . $this->siteName(),
        'description' => 'Hubungi RAMStudio untuk konsultasi gratis seputar pembuatan website, sistem informasi, dan solusi digital untuk bisnis Anda.',
      ]),
      'services' => $services,
      'faqs' => $this->faqs(6),
      'faqTotal' => $this->faqTotal(),
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
