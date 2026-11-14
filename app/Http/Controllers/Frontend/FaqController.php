<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as ViewFacade;

/**
 * Public FAQ centre.
 *
 * Renders every active FAQ managed from the admin dashboard
 * (`/admin/faqs`) on a single, searchable page, so visitors are not
 * limited to the six highlights shown on the homepage and contact page.
 */
class FaqController extends FrontendController
{
  public function index(): View
  {
    $this->boot();

    $faqs = Faq::query()
      ->active()
      ->orderBy('sort_order')
      ->orderBy('id')
      ->get();

    return view('frontend.faqs.index', [
      'seo' => $this->seo([
        'title' => 'Pertanyaan Umum (FAQ) — ' . $this->siteName(),
        'description' => 'Kumpulan pertanyaan yang paling sering diajukan klien seputar layanan pembuatan website, sistem informasi, harga, dan proses kerja sama bersama '
          . $this->siteName() . '.',
        'schema_extra' => [$this->faqSchema($faqs)],
      ]),
      'faqs' => $faqs,
      'faqTotal' => $faqs->count(),
      'faqHelpUrl' => ViewFacade::shared('whatsappUrl') ?: route('contact'),
      'faqHelpExternal' => (bool) ViewFacade::shared('whatsappUrl'),
    ]);
  }

  /**
   * Build a schema.org FAQPage payload so the questions are eligible
   * for rich results in search engines.
   *
   * @param  Collection<int, Faq>  $faqs
   * @return array<string, mixed>
   */
  private function faqSchema(Collection $faqs): array
  {
    return [
      '@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => $faqs->map(fn(Faq $faq) => [
        '@type' => 'Question',
        'name' => $faq->question,
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => trim(preg_replace('/\s+/', ' ', strip_tags((string) $faq->answer))),
        ],
      ])->values()->all(),
    ];
  }

  private function siteName(): string
  {
    return (string) (ViewFacade::shared('site')['name'] ?? 'RAMStudio');
  }
}
