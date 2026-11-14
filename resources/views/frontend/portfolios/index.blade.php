@extends('frontend.layouts.app')

@section('content')
     @include('frontend.partials.pagehead', [
         'title' => 'Portofolio',
         'subtitle' =>
             'Kumpulan proyek yang telah kami kerjakan untuk klien dari berbagai sektor dan skala bisnis.',
         'eyebrow' => 'Karya Kami',
         'breadcrumbs' => [['label' => 'Portofolio']],
     ])

     <section class="rs-section">
          <div class="rs-container">
               {{-- Summary --}}
               <div class="rs-summary" data-reveal>
                    <div class="rs-summary__item">
                         <i class="bi bi-collection"></i>
                         <div>
                              <strong data-count="{{ $totalProjects }}">{{ $totalProjects }}</strong>
                              <span>Proyek Selesai</span>
                         </div>
                    </div>
                    <div class="rs-summary__item">
                         <i class="bi bi-people"></i>
                         <div>
                              <strong data-count="{{ $totalClients }}">{{ $totalClients }}</strong>
                              <span>Klien Dilayani</span>
                         </div>
                    </div>
                    <div class="rs-summary__item">
                         <i class="bi bi-diagram-3"></i>
                         <div>
                              <strong data-count="{{ $categories->count() }}">{{ $categories->count() }}</strong>
                              <span>Bidang Industri</span>
                         </div>
                    </div>
                    <div class="rs-summary__item">
                         <i class="bi bi-stack"></i>
                         <div>
                              <strong data-count="{{ $technologies->count() }}">{{ $technologies->count() }}</strong>
                              <span>Teknologi Dipakai</span>
                         </div>
                    </div>
               </div>

               {{-- Search + filter toolbar --}}
               <form method="GET" action="{{ route('portfolios.index') }}" class="rs-toolbar" data-reveal
                    data-rs-pf-toolbar>
                    @if ($activeCategory)
                         <input type="hidden" name="kategori" value="{{ $activeCategory->slug }}">
                    @endif

                    <div class="rs-toolbar__search">
                         <i class="bi bi-search"></i>
                         <input type="search" name="q" value="{{ $search }}"
                              placeholder="Cari proyek, klien, atau kata kunci…" aria-label="Cari proyek">
                         @if ($search !== '')
                              <a href="{{ route('portfolios.index', array_filter(['kategori' => $activeCategory?->slug, 'teknologi' => $activeTechnology?->slug])) }}"
                                   class="rs-toolbar__clear" aria-label="Hapus pencarian">
                                   <i class="bi bi-x-lg"></i>
                              </a>
                         @endif
                    </div>

                    <div class="rs-toolbar__select">
                         <select name="teknologi" class="form-select" aria-label="Filter teknologi" data-rs-pf-tech>
                              <option value="">Semua Teknologi</option>
                              @foreach ($technologies as $tech)
                                   <option value="{{ $tech->slug }}"
                                        {{ $activeTechnology && $activeTechnology->id === $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                   </option>
                              @endforeach
                         </select>
                    </div>

                    <button type="submit" class="btn btn-rs-primary btn-sm">
                         <i class="bi bi-funnel"></i>
                         Terapkan
                    </button>

                    <span class="rs-toolbar__count">
                         <strong>{{ $portfolios->total() }}</strong> proyek ditemukan
                    </span>
               </form>

               {{-- Category filters --}}
               @if ($categories->isNotEmpty())
                    <div class="rs-filters" data-reveal>
                         @php
                              $baseQuery = array_filter([
                                  'q' => $search !== '' ? $search : null,
                                  'teknologi' => $activeTechnology?->slug,
                              ]);
                         @endphp
                         <a href="{{ route('portfolios.index', $baseQuery) }}"
                              class="rs-filter {{ !$activeCategory ? 'is-active' : '' }}">
                              Semua Proyek
                         </a>
                         @foreach ($categories as $category)
                              <a href="{{ route('portfolios.index', array_merge($baseQuery, ['kategori' => $category->slug])) }}"
                                   class="rs-filter {{ $activeCategory && $activeCategory->id === $category->id ? 'is-active' : '' }}">
                                   {{ $category->name }}
                              </a>
                         @endforeach
                    </div>
               @endif

               {{-- Active filters --}}
               @if ($activeCategory || $activeTechnology || $search !== '')
                    <div class="rs-active-filters" data-reveal>
                         <span class="rs-active-filters__label">Filter aktif</span>

                         @if ($activeCategory)
                              <span class="rs-active-filters__tag">
                                   <i class="bi bi-folder2"></i>
                                   {{ $activeCategory->name }}
                                   <a href="{{ route(
                                       'portfolios.index',
                                       array_filter([
                                           'q' => $search !== '' ? $search : null,
                                           'teknologi' => $activeTechnology?->slug,
                                       ]),
                                   ) }}"
                                        aria-label="Hapus filter kategori">
                                        <i class="bi bi-x-lg"></i>
                                   </a>
                              </span>
                         @endif

                         @if ($activeTechnology)
                              <span class="rs-active-filters__tag">
                                   <i class="bi bi-cpu"></i>
                                   {{ $activeTechnology->name }}
                                   <a href="{{ route(
                                       'portfolios.index',
                                       array_filter([
                                           'q' => $search !== '' ? $search : null,
                                           'kategori' => $activeCategory?->slug,
                                       ]),
                                   ) }}"
                                        aria-label="Hapus filter teknologi">
                                        <i class="bi bi-x-lg"></i>
                                   </a>
                              </span>
                         @endif

                         @if ($search !== '')
                              <span class="rs-active-filters__tag">
                                   <i class="bi bi-search"></i>
                                   &ldquo;{{ $search }}&rdquo;
                                   <a href="{{ route(
                                       'portfolios.index',
                                       array_filter([
                                           'kategori' => $activeCategory?->slug,
                                           'teknologi' => $activeTechnology?->slug,
                                       ]),
                                   ) }}"
                                        aria-label="Hapus pencarian">
                                        <i class="bi bi-x-lg"></i>
                                   </a>
                              </span>
                         @endif

                         <a href="{{ route('portfolios.index') }}" class="rs-active-filters__reset">
                              Reset semua
                         </a>
                    </div>
               @endif

               <div class="row g-4">
                    @forelse ($portfolios as $index => $portfolio)
                         <div class="col-md-6 col-lg-4">
                              <article class="rs-pf-card" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                   <a href="{{ route('portfolios.show', $portfolio->slug) }}" class="rs-pf-card__media">
                                        @if ($portfolio->thumbnail)
                                             <img src="{{ \App\Support\Media::url($portfolio->thumbnail) }}"
                                                  alt="{{ $portfolio->title }}" loading="lazy">
                                        @else
                                             <div class="rs-pf-card__placeholder"><i class="bi bi-image"></i></div>
                                        @endif

                                        @if ($portfolio->is_featured)
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
                                             @if ($portfolio->category)
                                                  <span><i class="bi bi-folder2"></i>{{ $portfolio->category->name }}</span>
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
                                             {{ \Illuminate\Support\Str::limit(strip_tags((string) $portfolio->description), 120) }}
                                        </p>

                                        @if ($portfolio->technologies->isNotEmpty())
                                             <div class="rs-pf-card__techs">
                                                  @foreach ($portfolio->technologies->take(3) as $tech)
                                                       <span class="rs-tech-tag">{{ $tech->name }}</span>
                                                  @endforeach
                                                  @if ($portfolio->technologies->count() > 3)
                                                       <span
                                                            class="rs-tech-tag">+{{ $portfolio->technologies->count() - 3 }}</span>
                                                  @endif
                                             </div>
                                        @endif
                                   </div>
                              </article>
                         </div>
                    @empty
                         <div class="col-12">
                              <div class="rs-empty">
                                   <i class="bi bi-search"></i>
                                   <h4>Proyek tidak ditemukan</h4>
                                   <p>
                                        @if ($search !== '' || $activeCategory || $activeTechnology)
                                             Coba ubah kata kunci atau reset filter yang aktif.
                                        @else
                                             Portofolio akan tampil di sini setelah ditambahkan melalui dashboard admin.
                                        @endif
                                   </p>
                                   @if ($search !== '' || $activeCategory || $activeTechnology)
                                        <a href="{{ route('portfolios.index') }}" class="rs-link-arrow mt-3">
                                             Reset semua filter
                                             <i class="bi bi-arrow-counterclockwise"></i>
                                        </a>
                                   @endif
                              </div>
                         </div>
                    @endforelse
               </div>

               {{-- Pagination --}}
               @if ($portfolios->hasPages())
                    <div class="rs-pagination d-flex justify-content-center mt-5">
                         {{ $portfolios->links() }}
                    </div>
               @endif
          </div>
     </section>

     @include('frontend.partials.cta', [
         'ctaTitle' => 'Ingin Proyek Anda Menjadi yang Berikutnya?',
         'ctaText' => 'Mari diskusikan ide dan kebutuhan Anda. Kami siap membantu mewujudkannya.',
     ])
@endsection
