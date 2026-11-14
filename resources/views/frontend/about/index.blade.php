@extends('frontend.layouts.app')

@section('content')
     @php
          $companyName = $site['name'] ?? 'RAMStudio';
          $vision = $values->firstWhere('type', 'vision');
          $mission = $values->firstWhere('type', 'mission');
          $coreValues = $values->where('type', 'value');
          $missionLines = $mission?->description ? preg_split('/\r\n|\r|\n/', trim($mission->description)) : [];
          $aboutImage = $site['about_image'] ?? ($site['hero_image'] ?? null);
          $yearsActive = $profile?->founded_year ? max(1, now()->year - (int) $profile->founded_year) : null;
          $locationQuery = $site['map_query'] ?? ($profile?->address ?? null);
          $locationUrl =
              $site['map_link'] ??
              ($locationQuery
                  ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($locationQuery)
                  : null);
     @endphp

     @include('frontend.partials.pagehead', [
         'title' => 'Tentang ' . $companyName,
         'subtitle' =>
             $profile?->tagline ?: 'Studio digital yang membangun solusi teknologi untuk pertumbuhan bisnis Anda.',
         'eyebrow' => 'Profil Perusahaan',
         'breadcrumbs' => [['label' => 'Tentang Kami']],
     ])

     {{-- ============================== PROFILE ============================== --}}
     <section class="rs-section rs-about-intro">
          <div class="rs-container">
               <div class="row g-4 g-lg-5 align-items-center">
                    {{-- Narrative column --}}
                    <div class="col-lg-6">
                         <div class="rs-about-intro__copy" data-reveal>
                              <span class="rs-eyebrow"><i class="bi bi-building"></i>Siapa Kami</span>

                              <h2 class="rs-about-intro__title">{{ $profile?->company_name ?? $companyName }}</h2>

                              @if ($profile?->tagline)
                                   <p class="rs-about-intro__tagline">{{ $profile->tagline }}</p>
                              @endif

                              @if ($profile?->description)
                                   <div class="rs-prose rs-about-intro__body">
                                        {!! nl2br(e($profile->description)) !!}
                                   </div>
                              @else
                                   <p class="rs-about-intro__body">
                                        {{ $companyName }} adalah studio digital yang berfokus pada pengembangan website,
                                        sistem informasi, dan aplikasi berbasis web. Kami membantu instansi pemerintah,
                                        perusahaan, UMKM, dan individu menghadirkan solusi digital yang profesional,
                                        fungsional, dan berkelanjutan.
                                   </p>
                              @endif

                              @php
                                   $leadStat = $statistics->first();
                                   $highlights = array_values(
                                       array_filter([
                                           $profile?->founded_year ? 'Aktif sejak ' . $profile->founded_year : null,
                                           $yearsActive ? $yearsActive . ' tahun pengalaman' : null,
                                           $leadStat
                                               ? number_format((float) $leadStat->value, 0, ',', '.') .
                                                   $leadStat->suffix .
                                                   ' ' .
                                                   \Illuminate\Support\Str::lower($leadStat->label)
                                               : null,
                                           'Tim multidisiplin & dukungan berkelanjutan',
                                       ]),
                                   );
                              @endphp

                              @if (!empty($highlights))
                                   <ul class="rs-about-intro__points">
                                        @foreach ($highlights as $point)
                                             <li>
                                                  <i class="bi bi-check2-circle"></i>
                                                  <span>{{ $point }}</span>
                                             </li>
                                        @endforeach
                                   </ul>
                              @endif

                              <div class="rs-about-intro__actions">
                                   <a href="{{ $whatsappUrl ?? route('contact') }}"
                                        @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                                        class="btn btn-rs-primary">
                                        <i class="bi bi-whatsapp"></i>
                                        Diskusikan Proyek
                                   </a>
                                   <a href="{{ route('services.index') }}" class="btn btn-rs-outline">
                                        Lihat Layanan
                                        <i class="bi bi-arrow-right"></i>
                                   </a>
                              </div>
                         </div>
                    </div>

                    {{-- Visual column --}}
                    <div class="col-lg-6">
                         <div class="rs-about-intro__media" data-reveal data-reveal-delay="80">
                              <div class="rs-about-intro__visual">
                                   <figure class="rs-about-intro__frame">
                                        @if ($aboutImage)
                                             <img src="{{ $aboutImage }}" alt="{{ $companyName }}" loading="lazy">
                                        @else
                                             <div class="rs-about-intro__placeholder">
                                                  <i class="bi bi-buildings"></i>
                                             </div>
                                        @endif
                                   </figure>

                                   @if ($yearsActive)
                                        <div class="rs-about-intro__badge">
                                             <i class="bi bi-award"></i>
                                             <div>
                                                  <strong>{{ $yearsActive }}+ Tahun</strong>
                                                  <span>Pengalaman</span>
                                             </div>
                                        </div>
                                   @endif
                              </div>

                              {{-- Location card — data dari profil/setting backend --}}
                              @if (!empty($site['address']))
                                   <a class="rs-about-intro__location"
                                        @if ($locationUrl) href="{{ $locationUrl }}" target="_blank" rel="noopener noreferrer" @else aria-disabled="true" @endif>
                                        <span class="rs-about-intro__location-icon"><i class="bi bi-geo-alt-fill"></i></span>
                                        <span class="rs-about-intro__location-body">
                                             <strong>Kantor Kami</strong>
                                             <span>{{ $site['address'] }}</span>
                                        </span>
                                        @if ($locationUrl)
                                             <i class="bi bi-box-arrow-up-right rs-about-intro__location-arrow"></i>
                                        @endif
                                   </a>
                              @endif
                         </div>
                    </div>
               </div>

               {{-- Identity strip --}}
               @php
                    $identityFacts = array_values(
                        array_filter([
                            $profile?->founded_year
                                ? [
                                    'icon' => 'bi bi-calendar-check',
                                    'label' => 'Berdiri Sejak',
                                    'value' => (string) $profile->founded_year,
                                ]
                                : null,
                            !empty($site['email'])
                                ? [
                                    'icon' => 'bi bi-envelope',
                                    'label' => 'Email',
                                    'value' => $site['email'],
                                    'url' => 'mailto:' . $site['email'],
                                ]
                                : null,
                            !empty($site['phone'])
                                ? [
                                    'icon' => 'bi bi-telephone',
                                    'label' => 'Telepon',
                                    'value' => $site['phone'],
                                    'url' => 'tel:' . preg_replace('/[^0-9+]/', '', $site['phone']),
                                ]
                                : null,
                            !empty($site['address'])
                                ? [
                                    'icon' => 'bi bi-geo-alt',
                                    'label' => 'Lokasi',
                                    'value' => $site['address'],
                                    'url' => $locationUrl,
                                ]
                                : null,
                        ]),
                    );
               @endphp

               @if (!empty($identityFacts))
                    <div class="rs-about-identity" data-reveal>
                         @foreach ($identityFacts as $fact)
                              <div class="rs-about-identity__item">
                                   <span class="rs-about-identity__icon"><i class="{{ $fact['icon'] }}"></i></span>
                                   <span class="rs-about-identity__text">
                                        <strong>{{ $fact['label'] }}</strong>
                                        @if (!empty($fact['url']))
                                             <a href="{{ $fact['url'] }}">{{ $fact['value'] }}</a>
                                        @else
                                             <span>{{ $fact['value'] }}</span>
                                        @endif
                                   </span>
                              </div>
                         @endforeach
                    </div>
               @endif
          </div>
     </section>

     {{-- ============================== VISION & MISSION ============================== --}}
     @if ($vision || $mission || $coreValues->isNotEmpty())
          <section class="rs-section rs-section--surface">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-compass"></i>Arah & Prinsip</span>
                         <h2>Visi, Misi, dan Nilai Kami</h2>
                         <p>Fondasi yang memandu setiap keputusan dan pekerjaan yang kami lakukan.</p>
                    </div>

                    @if ($vision || $mission)
                         <div class="rs-vm">
                              @if ($vision)
                                   <div class="rs-vm__panel rs-vm__panel--dark" data-reveal>
                                        <div class="rs-vm__icon"><i class="bi bi-eye"></i></div>
                                        <h3>{{ $vision->title ?: 'Visi' }}</h3>
                                        <p>{{ $vision->description }}</p>
                                   </div>
                              @endif

                              @if ($mission)
                                   <div class="rs-vm__panel" data-reveal data-reveal-delay="80">
                                        <div class="rs-vm__icon"><i class="bi bi-bullseye"></i></div>
                                        <h3>{{ $mission->title ?: 'Misi' }}</h3>
                                        @if (count($missionLines) > 1)
                                             <ul class="rs-vm__list">
                                                  @foreach ($missionLines as $line)
                                                       @if (trim($line) !== '')
                                                            <li>
                                                                 <span>{{ trim(preg_replace('/^[-•*]\s*/', '', $line)) }}</span>
                                                            </li>
                                                       @endif
                                                  @endforeach
                                             </ul>
                                        @else
                                             <p>{{ $mission->description }}</p>
                                        @endif
                                   </div>
                              @endif
                         </div>
                    @endif

                    @if ($coreValues->isNotEmpty())
                         <div class="row g-4 {{ $vision || $mission ? 'mt-1' : '' }}">
                              @foreach ($coreValues as $index => $value)
                                   <div class="col-md-6 col-lg-4">
                                        <div class="rs-value" data-reveal data-reveal-delay="{{ $index * 70 }}">
                                             <span class="rs-value__index" aria-hidden="true">
                                                  {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                             </span>

                                             <div class="rs-value__icon">
                                                  @php
                                                       $icon = $value->icon;
                                                       if (!$icon || !str_contains($icon, 'bi-')) {
                                                           $icons = [
                                                               'bi bi-shield-check',
                                                               'bi bi-lightbulb',
                                                               'bi bi-people',
                                                               'bi bi-gem',
                                                               'bi bi-heart-pulse',
                                                               'bi bi-rocket-takeoff',
                                                           ];
                                                           $icon = $icons[$loop->index % count($icons)];
                                                       }
                                                  @endphp
                                                  <i class="{{ $icon }}"></i>
                                             </div>
                                             <h3>{{ $value->title }}</h3>
                                             <p>{{ $value->description }}</p>
                                        </div>
                                   </div>
                              @endforeach
                         </div>
                    @endif
               </div>
          </section>
     @endif

     {{-- ============================== JOURNEY ============================== --}}
     @if ($journeys->isNotEmpty())
          <section class="rs-section">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-signpost-split"></i>Perjalanan</span>
                         <h2>Sejarah & Latar Belakang</h2>
                         <p>Perjalanan kami dari awal berdiri hingga hari ini.</p>
                    </div>

                    <div class="row justify-content-center">
                         <div class="col-lg-10">
                              <ol class="rs-timeline">
                                   @foreach ($journeys as $index => $journey)
                                        <li class="rs-timeline__item" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                             <span class="rs-timeline__dot" aria-hidden="true">
                                                  <i class="bi bi-flag"></i>
                                             </span>
                                             <span class="rs-timeline__year">{{ $journey->year }}</span>
                                             <h4>{{ $journey->title }}</h4>
                                             <p>{{ $journey->description }}</p>
                                        </li>
                                   @endforeach
                              </ol>
                         </div>
                    </div>
               </div>
          </section>
     @endif

     {{-- ============================== STATISTICS ============================== --}}
     @if ($statistics->isNotEmpty())
          <section class="rs-section rs-section--navy">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-graph-up"></i>Pencapaian</span>
                         <h2>Pengalaman yang Terukur</h2>
                    </div>
                    <div class="rs-stats">
                         @foreach ($statistics as $index => $stat)
                              <div class="rs-stat" data-reveal data-reveal-delay="{{ $index * 70 }}">
                                   <div class="rs-stat__value">
                                        <span
                                             data-count="{{ preg_replace('/[^0-9.]/', '', $stat->value) }}">{{ $stat->value }}</span><span
                                             class="suffix">{{ $stat->suffix }}</span>
                                   </div>
                                   <div class="rs-stat__label">{{ $stat->label }}</div>
                              </div>
                         @endforeach
                    </div>
               </div>
          </section>
     @endif

     {{-- ============================== FOUNDER ============================== --}}
     @if ($owners->isNotEmpty())
          <noscript>
               <style>
                    .rs-founder__more {
                         grid-template-rows: 1fr !important;
                    }

                    .rs-founder__toggle {
                         display: none !important;
                    }
               </style>
          </noscript>

          <section class="rs-section rs-founder-section">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-person-badge"></i>Founder</span>
                         <h2>Di Balik {{ $companyName }}</h2>
                         <p>Orang-orang yang memimpin dan menjaga standar kualitas setiap proyek.</p>
                    </div>

                    <div class="rs-founder-list">
                         @foreach ($owners as $owner)
                              @php
                                   $paragraphs = preg_split('/\r\n|\r|\n/', trim((string) $owner->bio));
                                   $paragraphs = array_values(array_filter(array_map('trim', $paragraphs)));
                                   $lead = $paragraphs[0] ?? null;
                                   $rest = array_slice($paragraphs, 1);
                                   $format = fn(string $text) => preg_replace(
                                       '/\*\*(.+?)\*\*/s',
                                       '<strong>$1</strong>',
                                       e($text),
                                   );
                                   $initials = strtoupper(\Illuminate\Support\Str::substr($owner->name, 0, 1));
                                   $moreId = 'founder-more-' . $owner->id;
                              @endphp

                              <article class="rs-founder" data-reveal>
                                   <div class="rs-founder__media">
                                        <span class="rs-founder__halo" aria-hidden="true"></span>

                                        <figure class="rs-founder__frame">
                                             @if ($owner->photo)
                                                  <img src="{{ \App\Support\Media::url($owner->photo) }}"
                                                       alt="{{ $owner->name }}" loading="lazy">
                                             @else
                                                  <div class="rs-founder__placeholder">{{ $initials }}</div>
                                             @endif
                                        </figure>

                                        <div class="rs-founder__badge">
                                             <i class="bi bi-patch-check-fill"></i>
                                             <span>{{ $owner->position ?: 'Founder' }}</span>
                                        </div>
                                   </div>

                                   <div class="rs-founder__body">
                                        <span class="rs-founder__quote" aria-hidden="true">&ldquo;</span>

                                        <span class="rs-eyebrow"><i class="bi bi-stars"></i>Founder &amp; Visioner</span>
                                        <h3 class="rs-founder__name">{{ $owner->name }}</h3>
                                        <p class="rs-founder__role">{{ $owner->position ?: 'Founder' }}</p>

                                        @if ($lead)
                                             <p class="rs-founder__lead">{!! $format($lead) !!}</p>
                                        @endif

                                        @if ($rest !== [])
                                             <div class="rs-founder__more" id="{{ $moreId }}" data-rs-founder-more>
                                                  <div class="rs-founder__more-inner">
                                                       @foreach ($rest as $paragraph)
                                                            <p>{!! $format($paragraph) !!}</p>
                                                       @endforeach
                                                  </div>
                                             </div>

                                             <button type="button" class="rs-founder__toggle" data-rs-founder-toggle
                                                  aria-controls="{{ $moreId }}" aria-expanded="false"
                                                  data-label-open="Baca selengkapnya" data-label-close="Tutup">
                                                  <span>Baca selengkapnya</span>
                                                  <i class="bi bi-chevron-down"></i>
                                             </button>
                                        @endif

                                        <div class="rs-founder__actions">
                                             @if ($owner->email)
                                                  <a href="mailto:{{ $owner->email }}" class="rs-founder__action">
                                                       <i class="bi bi-envelope"></i>
                                                       <span>Email</span>
                                                  </a>
                                             @endif
                                             @if ($owner->linkedin_url)
                                                  <a href="{{ $owner->linkedin_url }}" target="_blank"
                                                       rel="noopener noreferrer" class="rs-founder__action">
                                                       <i class="bi bi-linkedin"></i>
                                                       <span>LinkedIn</span>
                                                  </a>
                                             @endif
                                             @if ($owner->instagram_url)
                                                  <a href="{{ $owner->instagram_url }}" target="_blank"
                                                       rel="noopener noreferrer" class="rs-founder__action">
                                                       <i class="bi bi-instagram"></i>
                                                       <span>Instagram</span>
                                                  </a>
                                             @endif
                                             <a href="{{ route('contact') }}"
                                                  class="rs-founder__action rs-founder__action--primary">
                                                  <i class="bi bi-chat-dots"></i>
                                                  <span>Konsultasi Proyek</span>
                                             </a>
                                        </div>
                                   </div>
                              </article>
                         @endforeach
                    </div>
               </div>
          </section>
     @endif

     {{-- ============================== TEAM ============================== --}}
     @if ($team->isNotEmpty())
          <section class="rs-section rs-section--surface">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-people"></i>Tim Kami</span>
                         <h2>Kolaborasi Profesional</h2>
                         <p>Tim multidisiplin yang menangani desain, pengembangan, dan manajemen proyek.</p>
                    </div>

                    <div class="row g-4 justify-content-center">
                         @foreach ($team as $index => $member)
                              <div class="col-6 col-md-4 col-lg-3">
                                   <div class="rs-team-card" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                        <div class="rs-team-card__photo">
                                             @if ($member->photo)
                                                  <img src="{{ \App\Support\Media::url($member->photo) }}"
                                                       alt="{{ $member->name }}" loading="lazy">
                                             @else
                                                  {{ strtoupper(\Illuminate\Support\Str::substr($member->name, 0, 1)) }}
                                             @endif
                                        </div>
                                        <h3>{{ $member->name }}</h3>
                                        <p>{{ $member->position }}</p>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </div>
          </section>
     @endif

     {{-- ============================== COMMITMENT ============================== --}}
     <section class="rs-section">
          <div class="rs-container">
               <div class="row g-5 align-items-center">
                    <div class="col-lg-5">
                         <div class="rs-heading mb-0" data-reveal>
                              <span class="rs-eyebrow"><i class="bi bi-hand-thumbs-up"></i>Komitmen</span>
                              <h2>Komitmen Kami terhadap Klien</h2>
                              <p>Kami percaya hubungan jangka panjang dibangun dari hasil kerja yang nyata dan komunikasi
                                   yang jujur.</p>
                         </div>
                    </div>
                    <div class="col-lg-7">
                         <div class="row g-4">
                              @php
                                   $commitments = [
                                       [
                                           'icon' => 'bi bi-chat-square-text',
                                           'title' => 'Komunikasi Transparan',
                                           'text' =>
                                               'Laporan progres berkala dan akses mudah ke tim yang menangani proyek Anda.',
                                       ],
                                       [
                                           'icon' => 'bi bi-patch-check',
                                           'title' => 'Kualitas Tanpa Kompromi',
                                           'text' =>
                                               'Setiap detail diuji sebelum peluncuran untuk memastikan hasil terbaik.',
                                       ],
                                       [
                                           'icon' => 'bi bi-arrow-repeat',
                                           'title' => 'Revisi yang Wajar',
                                           'text' =>
                                               'Kami memastikan hasil akhir benar-benar sesuai dengan kebutuhan Anda.',
                                       ],
                                       [
                                           'icon' => 'bi bi-life-preserver',
                                           'title' => 'Dukungan Pasca Proyek',
                                           'text' =>
                                               'Bantuan teknis dan pemeliharaan setelah website atau sistem diluncurkan.',
                                       ],
                                   ];
                              @endphp

                              @foreach ($commitments as $index => $item)
                                   <div class="col-sm-6">
                                        <div class="rs-feature" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                             <div class="rs-feature__icon"><i class="{{ $item['icon'] }}"></i></div>
                                             <div>
                                                  <h4>{{ $item['title'] }}</h4>
                                                  <p>{{ $item['text'] }}</p>
                                             </div>
                                        </div>
                                   </div>
                              @endforeach
                         </div>
                    </div>
               </div>
          </div>
     </section>

     @include('frontend.partials.cta', [
         'ctaTitle' => 'Mari Berkenalan Lebih Dekat',
         'ctaText' => 'Ceritakan kebutuhan digital Anda. Kami siap membantu menemukan solusi yang tepat.',
     ])
@endsection
