<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutProfile;
use App\Models\AboutSocialLink;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Setting;
use App\Support\Media;
use App\Support\WhatsApp;
use Illuminate\Support\Facades\View;

/**
 * Shared base controller for every public frontend page.
 *
 * Loads the global site data (settings, company profile, social links and
 * the navigation service list) once and shares it with all Blade views so
 * the navbar, footer and SEO partials stay in sync with the admin dashboard.
 */
abstract class FrontendController extends Controller
{
  /**
   * Boot the shared view data. Called from each action via `boot()`.
   */
  protected function boot(): void
  {
    if (View::shared('site')) {
      return;
    }

    $profile = AboutProfile::current();

    $address = Setting::get('company_address', $profile?->address);

    $site = [
      'name' => Setting::get('site_name', $profile?->company_name ?? 'RAMStudio'),
      'tagline' => Setting::get('site_tagline', $profile?->tagline ?? 'Creative Digital Studio'),
      'description' => $profile?->description,
      'email' => Setting::get('contact_email', $profile?->email),
      'phone' => Setting::get('contact_phone', $profile?->phone),
      'address' => $address,
      'logo' => Media::url($profile?->logo),
      'hero_image' => Media::url($profile?->hero_image),
      'about_image' => Media::url($profile?->about_image),
      'founded_year' => $profile?->founded_year,
      'profile' => $profile,
    ];

    // Location map: an admin-supplied embed URL wins, otherwise fall back to a
    // keyless Google Maps embed built from the company address.
    $mapEmbed = Setting::get('map_embed_url');
    $mapQuery = Setting::get('map_query') ?: $address;

    $site['map_embed'] = $mapEmbed ?: ($mapQuery
      ? 'https://maps.google.com/maps?q=' . rawurlencode($mapQuery) . '&z=16&output=embed'
      : null);

    $site['map_link'] = $mapQuery
      ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapQuery)
      : null;

    $site['map_query'] = $mapQuery;

    $socialLinks = AboutSocialLink::query()
      ->active()
      ->orderBy('sort_order')
      ->get();

    $navServices = Service::query()
      ->active()
      ->orderBy('name')
      ->limit(6)
      ->get(['id', 'name', 'slug']);

    $whatsapp = $this->whatsappNumber($socialLinks->firstWhere('platform', 'WhatsApp')?->url, $site['phone']);

    View::share('site', $site);
    View::share('socialLinks', $socialLinks);
    View::share('navServices', $navServices);
    View::share('whatsappNumber', $whatsapp);
    View::share('whatsappUrl', WhatsApp::link($whatsapp));
  }

  /**
   * Normalise a WhatsApp URL or phone number into a wa.me compatible number.
   */
  protected function whatsappNumber(?string $url, ?string $phone): ?string
  {
    return WhatsApp::number($url, $phone);
  }

  /**
   * Build the SEO payload consumed by the `partials.seo` component.
   */
  protected function seo(array $overrides = []): array
  {
    $site = View::shared('site') ?? [];

    return array_merge([
      'title' => $site['name'] ?? 'RAMStudio',
      'description' => $site['description'] ?? ($site['tagline'] ?? ''),
      'image' => $site['hero_image'] ?? null,
      'type' => 'website',
      'canonical' => url()->current(),
      'robots' => 'index, follow',
    ], $overrides);
  }

  /**
   * Frequently asked questions shown on the homepage and contact page.
   */
  protected function faqs(int $limit = 6)
  {
    return Faq::query()
      ->active()
      ->orderBy('sort_order')
      ->limit($limit)
      ->get();
  }

  /**
   * Total number of active FAQs, used to advertise the full FAQ centre
   * whenever only a short highlight list is rendered.
   */
  protected function faqTotal(): int
  {
    return Faq::query()->active()->count();
  }
}
