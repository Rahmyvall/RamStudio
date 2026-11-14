<?php

namespace App\Http\Controllers\Frontend;

use App\Models\AboutJourney;
use App\Models\AboutOwner;
use App\Models\AboutStatistic;
use App\Models\AboutTeam;
use App\Models\AboutValue;
use Illuminate\Contracts\View\View;

class AboutController extends FrontendController
{
  public function index(): View
  {
    $this->boot();

    $owners = AboutOwner::query()
      ->active()
      ->orderBy('sort_order')
      ->get();

    $team = AboutTeam::query()
      ->active()
      ->orderBy('sort_order')
      ->get();

    $values = AboutValue::query()
      ->active()
      ->orderBy('sort_order')
      ->get();

    $journeys = AboutJourney::query()
      ->active()
      ->chronological()
      ->get();

    $statistics = AboutStatistic::query()
      ->active()
      ->orderBy('sort_order')
      ->get();

    $profile = \Illuminate\Support\Facades\View::shared('site')['profile'] ?? null;

    return view('frontend.about.index', [
      'seo' => $this->seo([
        'title' => 'Tentang Kami — ' . ($this->siteName()),
        'description' => $profile?->description
          ? \Illuminate\Support\Str::limit(strip_tags($profile->description), 155)
          : 'Kenali RAMStudio lebih dekat: profil, visi misi, nilai, perjalanan, dan tim di balik setiap proyek digital.',
      ]),
      'profile' => $profile,
      'owners' => $owners,
      'team' => $team,
      'values' => $values,
      'journeys' => $journeys,
      'statistics' => $statistics,
    ]);
  }

  private function siteName(): string
  {
    return (string) (\Illuminate\Support\Facades\View::shared('site')['name'] ?? 'RAMStudio');
  }
}
