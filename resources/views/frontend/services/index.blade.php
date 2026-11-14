@extends('frontend.layouts.app')

@section('content')
     @include('frontend.partials.pagehead', [
         'title' => 'Layanan Kami',
         'subtitle' =>
             'Solusi digital end-to-end untuk membangun, mengembangkan, dan mengoptimalkan kehadiran digital bisnis Anda.',
         'eyebrow' => 'Apa yang Kami Lakukan',
         'breadcrumbs' => [['label' => 'Layanan']],
     ])

     <section class="rs-section">
          <div class="rs-container">
               {{-- Capability strip --}}
               <div class="rs-capbar" data-reveal>
                    <div class="rs-capbar__item">
                         <i class="bi bi-collection"></i>
                         <div>
                              <strong>{{ $services->count() }}</strong>
                              <span>Layanan Tersedia</span>
                         </div>
                    </div>
                    <div class="rs-capbar__item">
                         <i class="bi bi-diagram-3"></i>
                         <div>
                              <strong>{{ $categories->count() }}</strong>
                              <span>Kategori Keahlian</span>
                         </div>
                    </div>
                    <div class="rs-capbar__item">
                         <i class="bi bi-patch-check"></i>
                         <div>
                              <strong>End-to-End</strong>
                              <span>Analisis s.d. Peluncuran</span>
                         </div>
                    </div>
                    <div class="rs-capbar__item">
                         <i class="bi bi-headset"></i>
                         <div>
                              <strong>Support</strong>
                              <span>Pendampingan Pasca Rilis</span>
                         </div>
                    </div>
               </div>

               {{-- Category filter --}}
               @if ($categories->isNotEmpty())
                    <div class="rs-filters" data-reveal>
                         <a href="{{ route('services.index') }}" class="rs-filter {{ !$activeCategory ? 'is-active' : '' }}">
                              Semua Layanan
                         </a>
                         @foreach ($categories as $category)
                              <a href="{{ route('services.index', ['kategori' => $category->slug]) }}"
                                   class="rs-filter {{ $activeCategory && $activeCategory->id === $category->id ? 'is-active' : '' }}">
                                   {{ $category->name }}
                              </a>
                         @endforeach
                    </div>
               @endif

               <div class="row g-4">
                    @forelse ($services as $index => $service)
                         <div class="col-md-6 col-lg-4">
                              <article class="rs-svc-card" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                   <div class="rs-svc-card__head">
                                        <div class="rs-svc-card__icon">
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
                                        <span class="rs-svc-card__index" aria-hidden="true">
                                             {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                   </div>

                                   @if ($service->is_featured)
                                        <span class="rs-svc-card__badge">
                                             <i class="bi bi-star-fill"></i> Unggulan
                                        </span>
                                   @endif

                                   @if ($service->category)
                                        <span class="rs-chip mb-2 align-self-start">{{ $service->category->name }}</span>
                                   @endif

                                   <h3>
                                        <a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a>
                                   </h3>
                                   <p class="rs-clamp-3">
                                        {{ $service->short_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $service->description), 130) }}
                                   </p>

                                   <div class="rs-svc-card__foot">
                                        <a href="{{ route('services.show', $service->slug) }}" class="rs-link-arrow">
                                             Detail layanan
                                             <i class="bi bi-arrow-right"></i>
                                        </a>
                                        @if ($service->starting_price)
                                             <span class="rs-svc-card__price">
                                                  Mulai
                                                  <strong>Rp
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
                                   <h4>Belum ada layanan</h4>
                                   <p>Layanan akan tampil di sini setelah ditambahkan melalui dashboard admin.</p>
                              </div>
                         </div>
                    @endforelse
               </div>
          </div>
     </section>

     {{-- Process --}}
     <section class="rs-section rs-section--surface">
          <div class="rs-container">
               <div class="rs-heading rs-heading--center" data-reveal>
                    <span class="rs-eyebrow"><i class="bi bi-diagram-3"></i>Alur Kerja</span>
                    <h2>Bagaimana Kami Bekerja</h2>
                    <p>Pendekatan terstruktur yang memastikan hasil sesuai ekspektasi dan tepat waktu.</p>
               </div>

               <div class="rs-steps rs-steps--connected">
                    @foreach ([['Konsultasi & Analisis', 'Memahami kebutuhan, tujuan, dan target pengguna Anda.'], ['Desain & Perencanaan', 'Menyusun struktur dan desain visual yang selaras dengan brand.'], ['Pengembangan', 'Implementasi kode bersih, aman, dan teruji.'], ['Peluncuran & Dukungan', 'Uji coba menyeluruh, peluncuran, dan pendampingan.']] as $index => $step)
                         <div class="rs-step" data-reveal data-reveal-delay="{{ $index * 80 }}">
                              <span class="rs-step__num">TAHAP {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                              <h4>{{ $step[0] }}</h4>
                              <p>{{ $step[1] }}</p>
                         </div>
                    @endforeach
               </div>
          </div>
     </section>

     @include('frontend.partials.cta', [
         'ctaTitle' => 'Butuh Layanan yang Spesifik?',
         'ctaText' => 'Kami siap menyesuaikan solusi dengan kebutuhan unik bisnis Anda. Diskusikan sekarang.',
     ])
@endsection
