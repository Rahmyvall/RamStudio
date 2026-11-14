@extends('frontend.layouts.app')

@section('content')
     @include('frontend.partials.pagehead', [
         'title' => 'Blog & Artikel',
         'subtitle' => 'Wawasan, tips, dan panduan seputar teknologi, desain, dan strategi digital.',
         'eyebrow' => 'Insight',
         'breadcrumbs' => [['label' => 'Blog']],
     ])

     <section class="rs-section">
          <div class="rs-container">
               <div class="row g-5">
                    {{-- Main list --}}
                    <div class="col-lg-8">
                         {{-- Search --}}
                         <form action="{{ route('blog.index') }}" method="GET" class="mb-4" data-reveal>
                              <div class="input-group">
                                   <input type="search" name="q" value="{{ $searchTerm }}" class="form-control"
                                        placeholder="Cari artikel..." aria-label="Cari artikel"
                                        style="border-radius:var(--rs-radius-pill) 0 0 var(--rs-radius-pill);border:1.5px solid var(--rs-line);padding:.75rem 1.15rem;">
                                   <button class="btn btn-rs-primary"
                                        style="border-radius:0 var(--rs-radius-pill) var(--rs-radius-pill) 0;"
                                        type="submit">
                                        <i class="bi bi-search"></i>
                                   </button>
                              </div>
                         </form>

                         {{-- Category filter --}}
                         @if ($categories->isNotEmpty())
                              <div class="rs-filters" data-reveal>
                                   <a href="{{ route('blog.index') }}"
                                        class="rs-filter {{ !$activeCategory && !$activeTag ? 'is-active' : '' }}">
                                        Semua
                                   </a>
                                   @foreach ($categories as $category)
                                        <a href="{{ route('blog.index', ['kategori' => $category->slug]) }}"
                                             class="rs-filter {{ $activeCategory && $activeCategory->id === $category->id ? 'is-active' : '' }}">
                                             {{ $category->name }}
                                        </a>
                                   @endforeach
                              </div>
                         @endif

                         @if ($activeTag)
                              <div class="rs-alert rs-alert--info mb-4">
                                   <i class="bi bi-tag"></i>
                                   <div>
                                        Menampilkan artikel dengan tag <strong>{{ $activeTag->name }}</strong>.
                                        <a href="{{ route('blog.index') }}" class="ms-1">Hapus filter</a>
                                   </div>
                              </div>
                         @endif

                         <div class="row g-4">
                              @forelse ($posts as $index => $post)
                                   <div class="col-md-6">
                                        <article class="rs-media-card" data-reveal data-reveal-delay="{{ $index * 60 }}">
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
                                                       <a
                                                            href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
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
                              @empty
                                   <div class="col-12">
                                        <div class="rs-empty">
                                             <i class="bi bi-journal-x"></i>
                                             <h4>Belum ada artikel</h4>
                                             <p>
                                                  @if ($searchTerm || $activeCategory || $activeTag)
                                                       Tidak ada artikel yang cocok dengan filter Anda.
                                                       <a href="{{ route('blog.index') }}">Reset filter</a>
                                                  @else
                                                       Artikel akan tampil di sini setelah dipublikasikan melalui
                                                       dashboard admin.
                                                  @endif
                                             </p>
                                        </div>
                                   </div>
                              @endforelse
                         </div>

                         @if ($posts->hasPages())
                              <div class="rs-pagination d-flex justify-content-center mt-5">
                                   {{ $posts->links() }}
                              </div>
                         @endif
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-lg-4">
                         <div class="position-sticky" style="top: calc(var(--rs-navbar-h) + 24px);">
                              @if ($categories->isNotEmpty())
                                   <div class="rs-card mb-4" data-reveal>
                                        <h3 class="h6 mb-3">Kategori</h3>
                                        <ul class="list-unstyled mb-0">
                                             @foreach ($categories as $category)
                                                  <li class="mb-2 pb-2" style="border-bottom:1px solid var(--rs-line);">
                                                       <a href="{{ route('blog.index', ['kategori' => $category->slug]) }}"
                                                            class="d-flex align-items-center justify-content-between gap-2"
                                                            style="color:var(--rs-ink);font-weight:600;font-size:.9rem;">
                                                            <span>{{ $category->name }}</span>
                                                            <i class="bi bi-arrow-right"
                                                                 style="color:var(--rs-blue-500);"></i>
                                                       </a>
                                                  </li>
                                             @endforeach
                                        </ul>
                                   </div>
                              @endif

                              @if ($popularTags->isNotEmpty())
                                   <div class="rs-card mb-4" data-reveal>
                                        <h3 class="h6 mb-3">Tag Populer</h3>
                                        <div class="d-flex flex-wrap gap-2">
                                             @foreach ($popularTags as $tag)
                                                  <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                                                       class="rs-tech-tag">#{{ $tag->name }}</a>
                                             @endforeach
                                        </div>
                                   </div>
                              @endif

                              <div class="rs-card" data-reveal>
                                   <div class="rs-card__icon"><i class="bi bi-chat-dots"></i></div>
                                   <h3 class="h6">Butuh Bantuan?</h3>
                                   <p>Diskusikan kebutuhan digital Anda dengan tim kami.</p>
                                   <a href="{{ $whatsappUrl ?? route('contact') }}"
                                        @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                                        class="btn btn-rs-primary w-100">
                                        <i class="bi {{ !empty($whatsappUrl) ? 'bi-whatsapp' : 'bi-chat-dots' }}"></i>
                                        Konsultasi Gratis
                                   </a>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </section>

     @include('frontend.partials.cta')
@endsection
