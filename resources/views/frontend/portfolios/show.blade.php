@extends('frontend.layouts.app')

@section('content')
     @php
          $thumbnail = \App\Support\Media::url($portfolio->thumbnail);
     @endphp

     @include('frontend.partials.pagehead', [
         'title' => $portfolio->title,
         'subtitle' => $portfolio->client_name ? 'Klien: ' . $portfolio->client_name : null,
         'eyebrow' => $portfolio->category?->name ?? 'Portofolio',
         'breadcrumbs' => [
             ['label' => 'Portofolio', 'url' => route('portfolios.index')],
             ['label' => $portfolio->title],
         ],
     ])

     <section class="rs-section">
          <div class="rs-container">
               <div class="row g-5">
                    <div class="col-lg-8">
                         {{-- Hero media --}}
                         <div class="rs-pf-hero" data-reveal>
                              @if ($thumbnail)
                                   <img src="{{ $thumbnail }}" alt="{{ $portfolio->title }}" loading="lazy">
                              @else
                                   <div class="rs-pf-hero__placeholder"><i class="bi bi-image"></i></div>
                              @endif
                         </div>

                         <div class="rs-prose" data-reveal>
                              @if ($portfolio->description)
                                   {!! $portfolio->description !!}
                              @else
                                   <p>Deskripsi proyek belum tersedia.</p>
                              @endif
                         </div>

                         {{-- Gallery --}}
                         @php
                              $gallery = collect();
                              if ($portfolio->thumbnail) {
                                  $gallery->push([
                                      'src' => \App\Support\Media::url($portfolio->thumbnail),
                                      'alt' => $portfolio->title,
                                  ]);
                              }
                              foreach ($portfolio->images as $image) {
                                  $gallery->push([
                                      'src' => \App\Support\Media::url($image->image_path),
                                      'alt' => $image->alt_text ?: $portfolio->title,
                                  ]);
                              }
                         @endphp

                         @if ($gallery->isNotEmpty())
                              <div class="rs-svc-block" data-reveal>
                                   <h2 class="rs-svc-block__title">Galeri Proyek</h2>
                                   <div class="rs-gallery" data-rs-gallery>
                                        @foreach ($gallery as $index => $photo)
                                             <button type="button" class="rs-gallery__item" data-rs-gallery-item
                                                  data-src="{{ $photo['src'] }}" data-alt="{{ $photo['alt'] }}"
                                                  data-index="{{ $index }}"
                                                  aria-label="Perbesar foto {{ $index + 1 }}: {{ $photo['alt'] }}">
                                                  <img src="{{ $photo['src'] }}" alt="{{ $photo['alt'] }}" loading="lazy">
                                                  <span class="rs-gallery__zoom"><i class="bi bi-zoom-in"></i></span>
                                             </button>
                                        @endforeach
                                   </div>
                                   <p class="rs-gallery__hint"><i class="bi bi-arrows-fullscreen"></i> Klik foto untuk
                                        memperbesar.</p>
                              </div>
                         @endif

                         {{-- Challenge & solution --}}
                         <div class="rs-svc-block" data-reveal>
                              <h2 class="rs-svc-block__title">Tantangan &amp; Solusi</h2>
                              <div class="row g-4">
                                   <div class="col-md-6">
                                        <div class="rs-cs rs-cs--challenge">
                                             <div class="rs-cs__icon"><i class="bi bi-exclamation-diamond"></i></div>
                                             <h3>Tantangan</h3>
                                             <p>
                                                  Menghadirkan solusi digital yang tidak hanya menarik secara visual,
                                                  tetapi juga mudah digunakan, cepat diakses, dan mampu menampung
                                                  pertumbuhan data serta kebutuhan bisnis ke depan.
                                             </p>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="rs-cs rs-cs--solution">
                                             <div class="rs-cs__icon"><i class="bi bi-lightbulb"></i></div>
                                             <h3>Solusi</h3>
                                             <p>
                                                  Kami merancang arsitektur yang terstruktur, antarmuka yang intuitif,
                                                  serta optimasi performa dan SEO agar sistem siap digunakan dalam jangka
                                                  panjang dan mudah dikembangkan.
                                             </p>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-lg-4">
                         <div class="rs-svc-panel">
                              <div class="rs-pf-meta" data-reveal>
                                   <div class="rs-pf-meta__head">
                                        <i class="bi bi-info-circle"></i>
                                        Detail Proyek
                                   </div>

                                   <div class="rs-pf-meta__body">
                                        @if ($portfolio->client_name)
                                             <div class="rs-pf-meta__row">
                                                  <span>Klien</span>
                                                  <strong>{{ $portfolio->client_name }}</strong>
                                             </div>
                                        @endif

                                        @if ($portfolio->category)
                                             <div class="rs-pf-meta__row">
                                                  <span>Kategori</span>
                                                  <strong>{{ $portfolio->category->name }}</strong>
                                             </div>
                                        @endif

                                        @if ($portfolio->project_date)
                                             <div class="rs-pf-meta__row">
                                                  <span>Tanggal</span>
                                                  <strong>{{ $portfolio->project_date->translatedFormat('d F Y') }}</strong>
                                             </div>
                                        @endif

                                        @if ($portfolio->technologies->isNotEmpty())
                                             <div class="rs-pf-meta__row">
                                                  <span>Teknologi</span>
                                                  <strong>{{ $portfolio->technologies->count() }} item</strong>
                                             </div>
                                        @endif
                                   </div>

                                   @if ($portfolio->technologies->isNotEmpty())
                                        <div class="rs-pf-meta__section">
                                             <span class="rs-pf-meta__label">Teknologi</span>
                                             <div class="d-flex flex-wrap gap-2">
                                                  @foreach ($portfolio->technologies as $tech)
                                                       <span class="rs-tech-tag">{{ $tech->name }}</span>
                                                  @endforeach
                                             </div>
                                        </div>
                                   @endif

                                   <div class="rs-pf-meta__actions">
                                        @if ($portfolio->project_url)
                                             <a href="{{ $portfolio->project_url }}" target="_blank"
                                                  rel="noopener noreferrer" class="btn btn-rs-primary w-100">
                                                  <i class="bi bi-box-arrow-up-right"></i>
                                                  Kunjungi Proyek
                                             </a>
                                        @endif

                                        <a href="{{ $whatsappUrl ?? route('contact') }}"
                                             @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                                             class="btn btn-rs-outline w-100">
                                             <i class="bi {{ !empty($whatsappUrl) ? 'bi-whatsapp' : 'bi-chat-dots' }}"></i>
                                             Buat Proyek Serupa
                                        </a>

                                        <a href="{{ route('portfolios.index') }}"
                                             class="rs-link-arrow justify-content-center mt-1">
                                             Kembali ke portofolio
                                             <i class="bi bi-arrow-left"></i>
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </section>

     {{-- Related --}}
     @if ($related->isNotEmpty())
          <section class="rs-section rs-section--surface">
               <div class="rs-container">
                    <div class="rs-heading" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-collection"></i>Proyek Lainnya</span>
                         <h2>Portofolio Terkait</h2>
                    </div>

                    <div class="row g-4">
                         @foreach ($related as $index => $item)
                              <div class="col-md-6 col-lg-4">
                                   <article class="rs-pf-card" data-reveal data-reveal-delay="{{ $index * 70 }}">
                                        <a href="{{ route('portfolios.show', $item->slug) }}" class="rs-pf-card__media">
                                             @if ($item->thumbnail)
                                                  <img src="{{ \App\Support\Media::url($item->thumbnail) }}"
                                                       alt="{{ $item->title }}" loading="lazy">
                                             @else
                                                  <div class="rs-pf-card__placeholder"><i class="bi bi-image"></i></div>
                                             @endif

                                             @if ($item->is_featured)
                                                  <span class="rs-pf-card__flag">
                                                       <i class="bi bi-star-fill"></i> Unggulan
                                                  </span>
                                             @endif

                                             <span class="rs-pf-card__open" aria-hidden="true">
                                                  <i class="bi bi-arrow-up-right"></i>
                                             </span>
                                        </a>

                                        <div class="rs-pf-card__body">
                                             <div class="rs-pf-card__top">
                                                  @if ($item->category)
                                                       <span><i class="bi bi-folder2"></i>{{ $item->category->name }}</span>
                                                  @endif
                                                  @if ($item->project_date)
                                                       <span><i
                                                                 class="bi bi-calendar3"></i>{{ $item->project_date->translatedFormat('M Y') }}</span>
                                                  @endif
                                             </div>

                                             <h3>
                                                  <a
                                                       href="{{ route('portfolios.show', $item->slug) }}">{{ $item->title }}</a>
                                             </h3>

                                             <p class="rs-clamp-2">
                                                  {{ \Illuminate\Support\Str::limit(strip_tags((string) $item->description), 100) }}
                                             </p>

                                             @if ($item->technologies->isNotEmpty())
                                                  <div class="rs-pf-card__techs">
                                                       @foreach ($item->technologies->take(3) as $tech)
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

     @include('frontend.partials.cta')
@endsection
