@extends('frontend.layouts.app')

@section('content')
     @php
          $profile = $site['profile'] ?? null;
          $heroImage = $site['hero_image'] ?? null;
          $statistics = $statistics ?? collect();
          $journeys = $journeys ?? collect();
     @endphp

     {{-- ============================== HERO ============================== --}}
     <section class="rs-hero">
          <div class="rs-container">
               <div class="rs-hero__grid">
                    <div>
                         <span class="rs-hero__badge">
                              <span class="dot"></span>
                              {{ $site['tagline'] ?? 'Digital Studio' }}
                              @if (!empty($site['founded_year']))
                                   · Sejak {{ $site['founded_year'] }}
                              @endif
                         </span>

                         <h1>
                              Bangun <span class="rs-gradient-text">Kehadiran Digital</span> yang Bekerja untuk Bisnis Anda
                         </h1>

                         <p class="rs-hero__lead">
                              {{ $site['name'] ?? 'RAMStudio' }} merancang dan membangun website, sistem informasi, serta
                              aplikasi web yang cepat, aman, dan siap mendukung pertumbuhan bisnis Anda.
                         </p>

                         <div class="rs-hero__actions">
                              <a href="{{ $whatsappUrl ?? route('contact') }}"
                                   @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                                   class="btn btn-rs-primary btn-lg">
                                   <i class="bi bi-whatsapp"></i>
                                   Konsultasi Gratis
                              </a>
                              <a href="{{ route('services.index') }}" class="btn btn-rs-ghost-light btn-lg">
                                   Lihat Layanan
                                   <i class="bi bi-arrow-right"></i>
                              </a>
                         </div>

                         <div class="rs-hero__trust">
                              @forelse ($statistics->take(3) as $stat)
                                   <div class="rs-hero__trust-item">
                                        <strong>
                                             <span
                                                  data-count="{{ preg_replace('/[^0-9.]/', '', $stat->value) }}">{{ $stat->value }}</span><span
                                                  class="suffix">{{ $stat->suffix }}</span>
                                        </strong>
                                        <span>{{ $stat->label }}</span>
                                   </div>
                              @empty
                                   <div class="rs-hero__trust-item">
                                        <strong>100<span class="suffix">+</span></strong>
                                        <span>Proyek Selesai</span>
                                   </div>
                                   <div class="rs-hero__trust-item">
                                        <strong>80<span class="suffix">+</span></strong>
                                        <span>Klien Puas</span>
                                   </div>
                                   <div class="rs-hero__trust-item">
                                        <strong>5<span class="suffix">+</span></strong>
                                        <span>Tahun Pengalaman</span>
                                   </div>
                              @endforelse
                         </div>
                    </div>

                    <div class="rs-hero__visual">
                         <div class="rs-hero__card">
                              <div class="rs-hero__chrome" aria-hidden="true">
                                   <span></span><span></span><span></span>
                                   <em>{{ strtolower($site['name'] ?? 'ramstudio') }}.id</em>
                              </div>

                              @if ($heroImage)
                                   <img src="{{ $heroImage }}" alt="{{ $site['name'] ?? 'RAMStudio' }}" loading="eager"
                                        fetchpriority="high">
                              @else
                                   <div class="d-flex align-items-center justify-content-center h-100"
                                        style="background: linear-gradient(150deg, #0b1a38, #16305c);">
                                        <i class="bi bi-code-slash"
                                             style="font-size: 5rem; color: rgba(107,164,255,.5);"></i>
                                   </div>
                              @endif
                         </div>

                         <div class="rs-float-card rs-float-card--tl">
                              <i class="bi bi-shield-check"></i>
                              <div>
                                   <strong>Kualitas Terjamin</strong>
                                   <span>Standar profesional</span>
                              </div>
                         </div>

                         <div class="rs-float-card rs-float-card--br">
                              <i class="bi bi-lightning-charge"></i>
                              <div>
                                   <strong>Performa Cepat</strong>
                                   <span>Optimasi kecepatan</span>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </section>

     {{-- ============================== TRUST STRIP ============================== --}}
     <div class="rs-logos">
          <div class="rs-container">
               <div class="rs-logos__inner">
                    <span class="rs-logos__label">Dipercaya oleh</span>
                    <ul class="rs-logos__items">
                         <li><i class="bi bi-building"></i>Instansi Pemerintah</li>
                         <li><i class="bi bi-shop"></i>UMKM &amp; Startup</li>
                         <li><i class="bi bi-mortarboard"></i>Institusi Pendidikan</li>
                         <li><i class="bi bi-heart-pulse"></i>Layanan Kesehatan</li>
                         <li><i class="bi bi-briefcase"></i>Perusahaan Swasta</li>
                    </ul>
               </div>
          </div>
     </div>

     {{-- ============================== SERVICES ============================== --}}
     <section class="rs-section">
          <div class="rs-container">
               <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
                    <div class="rs-heading mb-0" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-grid-1x2"></i>Layanan Unggulan</span>
                         <h2>Solusi Digital Lengkap untuk Setiap Kebutuhan</h2>
                         <p>Dari website company profile hingga sistem informasi kustom, kami menangani seluruh siklus
                              pengembangan.</p>
                    </div>
                    <a href="{{ route('services.index') }}" class="rs-link-arrow" data-reveal>
                         Semua layanan
                         <i class="bi bi-arrow-right"></i>
                    </a>
               </div>

               <div class="row g-4">
                    @forelse ($featuredServices as $index => $service)
                         <div class="col-md-6 col-lg-4">
                              <article class="rs-card" data-reveal data-reveal-delay="{{ $index * 70 }}">
                                   <div class="rs-card__icon">
                                        @php
                                             $icon = $service->icon;
                                             if (!$icon || !str_contains($icon, 'bi-')) {
                                                 $icons = [
                                                     'bi bi-window-stack',
                                                     'bi bi-database-gear',
                                                     'bi bi-phone',
                                                     'bi bi-speedometer2',
                                                     'bi bi-palette',
                                                     'bi bi-headset',
                                                 ];
                                                 $icon = $icons[$loop->index % count($icons)];
                                             }
                                        @endphp
                                        <i class="{{ $icon }}"></i>
                                   </div>

                                   @if ($service->category)
                                        <span class="rs-chip mb-2 align-self-start">{{ $service->category->name }}</span>
                                   @endif

                                   <h3>{{ $service->name }}</h3>
                                   <p class="rs-clamp-3">
                                        {{ $service->short_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $service->description), 120) }}
                                   </p>

                                   <div class="rs-card__footer d-flex align-items-center justify-content-between">
                                        <a href="{{ route('services.show', $service->slug) }}" class="rs-link-arrow">
                                             Selengkapnya
                                             <i class="bi bi-arrow-right"></i>
                                        </a>
                                        @if ($service->starting_price)
                                             <span class="rs-text-muted" style="font-size:.8rem;">
                                                  Mulai
                                                  <strong class="rs-text-ink">Rp
                                                       {{ number_format((float) $service->starting_price, 0, ',', '.') }}</strong>
                                             </span>
                                        @endif
                                   </div>
                              </article>
                         </div>
                    @empty
                         <div class="col-12">
                              <div class="rs-empty">
                                   <i class="bi bi-inbox"></i>
                                   <h4>Layanan belum tersedia</h4>
                                   <p>Konten layanan akan tampil di sini setelah ditambahkan melalui dashboard admin.</p>
                              </div>
                         </div>
                    @endforelse
               </div>
          </div>
     </section>

     {{-- ============================== WHY US ============================== --}}
     <section class="rs-section rs-section--surface">
          <div class="rs-container">
               <div class="row g-5 align-items-center">
                    <div class="col-lg-5">
                         <div class="rs-heading mb-0" data-reveal>
                              <span class="rs-eyebrow"><i class="bi bi-award"></i>Mengapa RAMStudio</span>
                              <h2>Partner Teknologi yang Tumbuh Bersama Anda</h2>
                              <p>Kami tidak sekadar membuat website. Kami membangun fondasi digital yang mendukung tujuan
                                   bisnis jangka panjang.</p>
                              <a href="{{ route('about') }}" class="rs-link-arrow mt-3">
                                   Kenali kami lebih dekat
                                   <i class="bi bi-arrow-right"></i>
                              </a>
                         </div>
                    </div>

                    <div class="col-lg-7">
                         <div class="row g-4">
                              @php
                                   $advantages = [
                                       [
                                           'icon' => 'bi bi-patch-check',
                                           'title' => 'Kualitas Profesional',
                                           'text' =>
                                               'Setiap proyek dikerjakan dengan standar kode dan desain yang rapi, terstruktur, dan mudah dikembangkan.',
                                       ],
                                       [
                                           'icon' => 'bi bi-clock-history',
                                           'title' => 'Tepat Waktu',
                                           'text' =>
                                               'Timeline jelas dan komunikasi transparan di setiap tahap pengerjaan proyek Anda.',
                                       ],
                                       [
                                           'icon' => 'bi bi-phone',
                                           'title' => 'Responsif Penuh',
                                           'text' =>
                                               'Tampilan optimal di desktop, laptop, tablet, dan smartphone tanpa kompromi.',
                                       ],
                                       [
                                           'icon' => 'bi bi-graph-up-arrow',
                                           'title' => 'Berorientasi Hasil',
                                           'text' =>
                                               'Fokus pada performa, SEO, dan pengalaman pengguna yang mendorong konversi.',
                                       ],
                                       [
                                           'icon' => 'bi bi-shield-lock',
                                           'title' => 'Aman & Terpercaya',
                                           'text' =>
                                               'Praktik keamanan terbaik untuk melindungi data dan aset digital Anda.',
                                       ],
                                       [
                                           'icon' => 'bi bi-headset',
                                           'title' => 'Dukungan Berkelanjutan',
                                           'text' =>
                                               'Pendampingan pasca peluncuran untuk memastikan sistem berjalan optimal.',
                                       ],
                                   ];
                              @endphp

                              @foreach ($advantages as $index => $item)
                                   <div class="col-sm-6">
                                        <div class="rs-feature" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                             <div class="rs-feature__icon">
                                                  <i class="{{ $item['icon'] }}"></i>
                                             </div>
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

     {{-- ============================== PORTFOLIO ============================== --}}
     @if ($featuredPortfolios->isNotEmpty())
          <section class="rs-section">
               <div class="rs-container">
                    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
                         <div class="rs-heading mb-0" data-reveal>
                              <span class="rs-eyebrow"><i class="bi bi-collection"></i>Portofolio</span>
                              <h2>Karya yang Berbicara</h2>
                              <p>Beberapa proyek pilihan yang telah kami kerjakan bersama klien dari berbagai industri.</p>
                         </div>
                         <a href="{{ route('portfolios.index') }}" class="rs-link-arrow" data-reveal>
                              Semua proyek
                              <i class="bi bi-arrow-right"></i>
                         </a>
                    </div>

                    <div class="row g-4">
                         @foreach ($featuredPortfolios as $index => $portfolio)
                              <div class="col-md-6 col-lg-4">
                                   <article class="rs-media-card" data-reveal data-reveal-delay="{{ $index * 80 }}">
                                        <a href="{{ route('portfolios.show', $portfolio->slug) }}"
                                             class="rs-media-card__media d-block">
                                             @if ($portfolio->thumbnail)
                                                  <img src="{{ \App\Support\Media::url($portfolio->thumbnail) }}"
                                                       alt="{{ $portfolio->title }}" loading="lazy">
                                             @else
                                                  <div class="rs-media-card__placeholder">
                                                       <i class="bi bi-image"></i>
                                                  </div>
                                             @endif
                                        </a>
                                        <div class="rs-media-card__body">
                                             <div class="rs-media-card__meta">
                                                  @if ($portfolio->category)
                                                       <span><i
                                                                 class="bi bi-folder2"></i>{{ $portfolio->category->name }}</span>
                                                  @endif
                                                  @if ($portfolio->project_date)
                                                       <span><i
                                                                 class="bi bi-calendar3"></i>{{ $portfolio->project_date->translatedFormat('M Y') }}</span>
                                                  @endif
                                             </div>
                                             <h3>
                                                  <a
                                                       href="{{ route('portfolios.show', $portfolio->slug) }}">{{ $portfolio->title }}</a>
                                             </h3>
                                             <p class="rs-clamp-2">
                                                  {{ \Illuminate\Support\Str::limit(strip_tags((string) $portfolio->description), 110) }}
                                             </p>
                                             @if ($portfolio->technologies->isNotEmpty())
                                                  <div class="d-flex flex-wrap gap-2 mt-auto">
                                                       @foreach ($portfolio->technologies->take(3) as $tech)
                                                            <span class="rs-tech-tag">{{ $tech->name }}</span>
                                                       @endforeach
                                                  </div>
                                             @endif
                                        </div>
                                   </article>
                              </div>
                         @endforeach
                    </div>
               </div>
          </section>
     @endif

     {{-- ============================== PROCESS ============================== --}}
     <section class="rs-section rs-section--surface">
          <div class="rs-container">
               <div class="rs-heading rs-heading--center" data-reveal>
                    <span class="rs-eyebrow"><i class="bi bi-diagram-3"></i>Alur Kerja</span>
                    <h2>Proses Pengerjaan yang Jelas</h2>
                    <p>Empat tahap terstruktur agar Anda selalu tahu posisi proyek dan hasil yang akan didapat.</p>
               </div>

               <div class="rs-steps rs-steps--connected">
                    @php
                         $steps = [
                             [
                                 'title' => 'Konsultasi & Analisis',
                                 'text' =>
                                     'Kami mendengarkan kebutuhan, tujuan bisnis, dan target audiens Anda secara mendalam.',
                             ],
                             [
                                 'title' => 'Desain & Perencanaan',
                                 'text' =>
                                     'Menyusun struktur, wireframe, dan desain visual yang selaras dengan identitas brand.',
                             ],
                             [
                                 'title' => 'Pengembangan',
                                 'text' =>
                                     'Implementasi kode yang bersih, aman, dan teruji sesuai spesifikasi yang disepakati.',
                             ],
                             [
                                 'title' => 'Peluncuran & Dukungan',
                                 'text' =>
                                     'Uji coba menyeluruh, peluncuran, serta pendampingan untuk memastikan hasil optimal.',
                             ],
                         ];
                    @endphp

                    @foreach ($steps as $index => $step)
                         <div class="rs-step" data-reveal data-reveal-delay="{{ $index * 80 }}">
                              <span class="rs-step__num">TAHAP {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                              <h4>{{ $step['title'] }}</h4>
                              <p>{{ $step['text'] }}</p>
                         </div>
                    @endforeach
               </div>
          </div>
     </section>

     {{-- ============================== STATS ============================== --}}
     @if ($statistics->isNotEmpty())
          <section class="rs-section rs-section--navy">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-bar-chart"></i>Pencapaian</span>
                         <h2>Angka yang Mencerminkan Komitmen Kami</h2>
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

     {{-- ============================== TESTIMONIALS ============================== --}}
     @include('frontend.partials.testimonials', ['testimonials' => $testimonials])

     {{-- ============================== PRICING ============================== --}}
     @if ($packages->isNotEmpty())
          <section class="rs-section rs-section--surface">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-tags"></i>Paket Harga</span>
                         <h2>Investasi yang Transparan</h2>
                         <p>Pilih paket yang sesuai, atau diskusikan kebutuhan kustom Anda dengan tim kami.</p>
                    </div>

                    <div class="row g-4 justify-content-center">
                         @foreach ($packages as $index => $package)
                              <div class="col-md-6 col-lg-4">
                                   <div class="rs-price-card {{ $package->is_popular ? 'rs-price-card--popular' : '' }}"
                                        data-reveal data-reveal-delay="{{ $index * 80 }}">
                                        @if ($package->is_popular)
                                             <span class="rs-price-card__badge">Paling Populer</span>
                                        @endif

                                        <h3>{{ $package->name }}</h3>
                                        <p class="rs-price-card__desc">{{ $package->description }}</p>

                                        <div class="rs-price-card__amount">
                                             @if ($package->price !== null)
                                                  Rp {{ number_format((float) $package->price, 0, ',', '.') }}
                                                  <small>{{ $package->price_label }}</small>
                                             @else
                                                  <small>Hubungi kami</small>
                                             @endif
                                        </div>

                                        <ul class="rs-price-card__features">
                                             @foreach ($package->features as $feature)
                                                  <li class="{{ $feature->is_included ? '' : 'is-excluded' }}">
                                                       <i
                                                            class="bi {{ $feature->is_included ? 'bi-check-circle-fill' : 'bi-x-circle' }}"></i>
                                                       <span>{{ $feature->feature }}</span>
                                                  </li>
                                             @endforeach
                                        </ul>

                                        @php
                                             $packageWa = \App\Support\WhatsApp::packageLink(
                                                 $whatsappNumber ?? null,
                                                 $package->name,
                                             );
                                        @endphp
                                        <a href="{{ $packageWa ?? route('contact') . '?paket=' . $package->slug }}"
                                             @if ($packageWa) target="_blank" rel="noopener noreferrer" @endif
                                             class="btn {{ $package->is_popular ? 'btn-rs-primary' : 'btn-rs-outline' }} w-100">
                                             Pilih Paket
                                             <i class="bi {{ $packageWa ? 'bi-whatsapp' : 'bi-arrow-right' }}"></i>
                                        </a>
                                   </div>
                              </div>
                         @endforeach
                    </div>

                    <div class="text-center mt-4" data-reveal>
                         <a href="{{ route('pricing') }}" class="rs-link-arrow">
                              Bandingkan semua paket
                              <i class="bi bi-arrow-right"></i>
                         </a>
                    </div>
               </div>
          </section>
     @endif

     {{-- ============================== BLOG ============================== --}}
     @if ($latestPosts->isNotEmpty())
          <section class="rs-section">
               <div class="rs-container">
                    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
                         <div class="rs-heading mb-0" data-reveal>
                              <span class="rs-eyebrow"><i class="bi bi-journal-text"></i>Insight</span>
                              <h2>Artikel Terbaru</h2>
                              <p>Wawasan seputar teknologi, desain, dan strategi digital untuk bisnis Anda.</p>
                         </div>
                         <a href="{{ route('blog.index') }}" class="rs-link-arrow" data-reveal>
                              Semua artikel
                              <i class="bi bi-arrow-right"></i>
                         </a>
                    </div>

                    <div class="row g-4">
                         @foreach ($latestPosts as $index => $post)
                              <div class="col-md-6 col-lg-4">
                                   <article class="rs-media-card" data-reveal data-reveal-delay="{{ $index * 80 }}">
                                        <a href="{{ route('blog.show', $post->slug) }}"
                                             class="rs-media-card__media d-block">
                                             @if ($post->featured_image)
                                                  <img src="{{ \App\Support\Media::url($post->featured_image) }}"
                                                       alt="{{ $post->title }}" loading="lazy">
                                             @else
                                                  <div class="rs-media-card__placeholder">
                                                       <i class="bi bi-file-earmark-text"></i>
                                                  </div>
                                             @endif
                                        </a>
                                        <div class="rs-media-card__body">
                                             <div class="rs-media-card__meta">
                                                  @if ($post->category)
                                                       <span><i
                                                                 class="bi bi-folder2"></i>{{ $post->category->name }}</span>
                                                  @endif
                                                  <span>
                                                       <i class="bi bi-calendar3"></i>
                                                       {{ ($post->published_at ?? $post->created_at)?->translatedFormat('d M Y') }}
                                                  </span>
                                             </div>
                                             <h3>
                                                  <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                             </h3>
                                             <p class="rs-clamp-2">
                                                  {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $post->content), 110) }}
                                             </p>
                                             <a href="{{ route('blog.show', $post->slug) }}"
                                                  class="rs-link-arrow mt-auto">
                                                  Baca artikel
                                                  <i class="bi bi-arrow-right"></i>
                                             </a>
                                        </div>
                                   </article>
                              </div>
                         @endforeach
                    </div>
               </div>
          </section>
     @endif

     {{-- ============================== FAQ ============================== --}}
     @include('frontend.partials.faq', ['faqs' => $faqs, 'faqId' => 'home-faq'])

     {{-- ============================== CTA ============================== --}}
     @include('frontend.partials.cta')
@endsection
