@extends('frontend.layouts.app')

@section('content')
     @php
          $image = \App\Support\Media::url($service->image);
     @endphp

     @include('frontend.partials.pagehead', [
         'title' => $service->name,
         'subtitle' => $service->short_description,
         'eyebrow' => $service->category?->name ?? 'Layanan',
         'breadcrumbs' => [['label' => 'Layanan', 'url' => route('services.index')], ['label' => $service->name]],
     ])

     <section class="rs-section">
          <div class="rs-container">
               <div class="row g-5">
                    {{-- Main content --}}
                    <div class="col-lg-8">
                         <div class="rs-svc-hero" data-reveal>
                              @if ($image)
                                   <img src="{{ $image }}" alt="{{ $service->name }}" loading="lazy">
                              @else
                                   <div class="rs-svc-hero__placeholder">
                                        <i class="bi bi-window-stack"></i>
                                   </div>
                              @endif

                              <div class="rs-svc-hero__overlay">
                                   @if ($service->category)
                                        <span class="rs-chip">{{ $service->category->name }}</span>
                                   @endif
                                   @if ($service->is_featured)
                                        <span class="rs-chip"><i class="bi bi-star-fill"></i> Layanan Unggulan</span>
                                   @endif
                              </div>
                         </div>

                         <div class="rs-svc-meta" data-reveal>
                              @if ($service->category)
                                   <div class="rs-svc-meta__item">
                                        <i class="bi bi-folder2"></i>
                                        <div>
                                             <strong>Kategori</strong>
                                             <span>{{ $service->category->name }}</span>
                                        </div>
                                   </div>
                              @endif
                              @if ($service->starting_price)
                                   <div class="rs-svc-meta__item">
                                        <i class="bi bi-tag"></i>
                                        <div>
                                             <strong>Harga Mulai</strong>
                                             <span>Rp
                                                  {{ number_format((float) $service->starting_price, 0, ',', '.') }}</span>
                                        </div>
                                   </div>
                              @endif
                              <div class="rs-svc-meta__item">
                                   <i class="bi bi-clock-history"></i>
                                   <div>
                                        <strong>Pengerjaan</strong>
                                        <span>Berdasarkan Kesepakatan</span>
                                   </div>
                              </div>
                              <div class="rs-svc-meta__item">
                                   <i class="bi bi-shield-check"></i>
                                   <div>
                                        <strong>Garansi</strong>
                                        <span>Dukungan Pasca Rilis</span>
                                   </div>
                              </div>
                         </div>

                         <div class="rs-prose" data-reveal>
                              @if ($service->description)
                                   {!! $service->description !!}
                              @else
                                   <p>{{ $service->short_description }}</p>
                              @endif
                         </div>

                         {{-- Benefits --}}
                         <div class="rs-svc-block" data-reveal>
                              <h2 class="rs-svc-block__title">Manfaat Layanan Ini</h2>
                              <div class="row g-4">
                                   @php
                                        $benefits = [
                                            [
                                                'icon' => 'bi bi-graph-up-arrow',
                                                'title' => 'Meningkatkan Kredibilitas',
                                                'text' =>
                                                    'Tampilan profesional membangun kepercayaan calon klien sejak kunjungan pertama.',
                                            ],
                                            [
                                                'icon' => 'bi bi-search',
                                                'title' => 'Siap untuk SEO',
                                                'text' =>
                                                    'Struktur HTML semantik dan performa optimal agar mudah ditemukan mesin pencari.',
                                            ],
                                            [
                                                'icon' => 'bi bi-phone',
                                                'title' => 'Responsif di Semua Perangkat',
                                                'text' => 'Pengalaman terbaik di desktop, tablet, maupun smartphone.',
                                            ],
                                            [
                                                'icon' => 'bi bi-gear-wide-connected',
                                                'title' => 'Mudah Dikelola',
                                                'text' =>
                                                    'Konten dapat diperbarui sendiri melalui dashboard administrasi.',
                                            ],
                                        ];
                                   @endphp

                                   @foreach ($benefits as $index => $benefit)
                                        <div class="col-sm-6">
                                             <div class="rs-feature" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                                  <div class="rs-feature__icon"><i class="{{ $benefit['icon'] }}"></i></div>
                                                  <div>
                                                       <h4>{{ $benefit['title'] }}</h4>
                                                       <p>{{ $benefit['text'] }}</p>
                                                  </div>
                                             </div>
                                        </div>
                                   @endforeach
                              </div>
                         </div>

                         {{-- Technologies --}}
                         <div class="rs-svc-block" data-reveal>
                              <h2 class="rs-svc-block__title">Teknologi yang Kami Gunakan</h2>
                              <p class="rs-text-muted mb-3">
                                   Kami memilih teknologi yang stabil, aman, dan mudah dikembangkan dalam jangka panjang.
                              </p>
                              <div class="rs-tech-grid">
                                   @foreach (['Laravel', 'PHP', 'MySQL', 'Bootstrap 5', 'JavaScript', 'REST API', 'Tailwind CSS', 'Vue.js'] as $tech)
                                        <span class="rs-tech-grid__item">
                                             <i class="bi bi-check2-circle"></i>
                                             {{ $tech }}
                                        </span>
                                   @endforeach
                              </div>
                         </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-lg-4">
                         <div class="rs-svc-panel">
                              <div class="rs-svc-panel__card rs-svc-panel__card--dark" data-reveal>
                                   <h3 class="h5 mb-2">Tertarik dengan layanan ini?</h3>
                                   <p class="mb-3" style="font-size:.9rem;">
                                        Konsultasikan kebutuhan Anda. Tim kami akan memberikan rekomendasi terbaik sesuai
                                        anggaran dan target Anda.
                                   </p>

                                   @if ($service->starting_price)
                                        <span class="rs-svc-panel__label">Harga mulai dari</span>
                                        <div class="rs-svc-panel__price">
                                             Rp {{ number_format((float) $service->starting_price, 0, ',', '.') }}
                                             <small>Dapat disesuaikan dengan lingkup pekerjaan</small>
                                        </div>
                                   @endif

                                   @php
                                        $serviceWa = \App\Support\WhatsApp::serviceLink(
                                            $whatsappNumber ?? null,
                                            $service->name,
                                        );
                                   @endphp

                                   <a href="{{ $serviceWa ?? route('contact') . '?layanan=' . $service->slug }}"
                                        @if ($serviceWa) target="_blank" rel="noopener noreferrer" @endif
                                        class="btn btn-rs-primary w-100 mb-2">
                                        <i class="bi {{ $serviceWa ? 'bi-whatsapp' : 'bi-chat-dots' }}"></i>
                                        Konsultasi Sekarang
                                   </a>

                                   <a href="{{ route('contact') }}?layanan={{ $service->slug }}"
                                        class="btn btn-rs-ghost-light w-100">
                                        <i class="bi bi-envelope"></i>
                                        Kirim Formulir
                                   </a>
                              </div>

                              @if ($related->isNotEmpty())
                                   <div class="rs-svc-panel__card mt-4" data-reveal>
                                        <h3 class="h6 mb-2">Layanan Lainnya</h3>
                                        <ul class="rs-svc-related">
                                             @foreach ($related as $item)
                                                  <li>
                                                       <a href="{{ route('services.show', $item->slug) }}">
                                                            <span>{{ $item->name }}</span>
                                                            <i class="bi bi-arrow-right"></i>
                                                       </a>
                                                  </li>
                                             @endforeach
                                        </ul>
                                   </div>
                              @endif
                         </div>
                    </div>
               </div>
          </div>
     </section>

     @include('frontend.partials.cta')
@endsection
