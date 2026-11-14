@extends('frontend.layouts.app')

@section('content')
     @php
          $image = \App\Support\Media::url($post->featured_image);
          $publishedAt = $post->published_at ?? $post->created_at;
     @endphp

     @include('frontend.partials.pagehead', [
         'title' => $post->title,
         'subtitle' => $post->excerpt,
         'eyebrow' => $post->category?->name ?? 'Artikel',
         'breadcrumbs' => [
             ['label' => 'Blog', 'url' => route('blog.index')],
             ['label' => \Illuminate\Support\Str::limit($post->title, 40)],
         ],
     ])

     <section class="rs-section">
          <div class="rs-container">
               <div class="row g-5">
                    <div class="col-lg-8">
                         {{-- Meta --}}
                         <div class="d-flex flex-wrap align-items-center gap-3 mb-4" data-reveal>
                              @if ($publishedAt)
                                   <span class="rs-chip rs-chip--neutral">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $publishedAt->translatedFormat('d F Y') }}
                                   </span>
                              @endif
                              @if ($post->author)
                                   <span class="rs-chip rs-chip--neutral">
                                        <i class="bi bi-person"></i>
                                        {{ $post->author->name }}
                                   </span>
                              @endif
                              @if ($post->category)
                                   <a href="{{ route('blog.index', ['kategori' => $post->category->slug]) }}" class="rs-chip">
                                        <i class="bi bi-folder2"></i>
                                        {{ $post->category->name }}
                                   </a>
                              @endif
                         </div>

                         @if ($image)
                              <img src="{{ $image }}" alt="{{ $post->title }}"
                                   class="w-100 rs-rounded-lg rs-shadow mb-4" style="aspect-ratio:16/9;object-fit:cover;"
                                   loading="lazy">
                         @endif

                         <article class="rs-prose" data-reveal>
                              {!! $post->content !!}
                         </article>

                         {{-- Tags --}}
                         @if ($post->tags->isNotEmpty())
                              <div class="mt-5 pt-4" style="border-top:1px solid var(--rs-line);" data-reveal>
                                   <span class="rs-text-muted d-block mb-2"
                                        style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">
                                        Tag
                                   </span>
                                   <div class="d-flex flex-wrap gap-2">
                                        @foreach ($post->tags as $tag)
                                             <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                                                  class="rs-tech-tag">#{{ $tag->name }}</a>
                                        @endforeach
                                   </div>
                              </div>
                         @endif

                         {{-- Share --}}
                         <div class="mt-4 d-flex flex-wrap align-items-center gap-2" data-reveal>
                              <span class="rs-text-muted me-1" style="font-size:.85rem;font-weight:600;">Bagikan:</span>
                              <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                                   target="_blank" rel="noopener noreferrer" class="btn btn-rs-outline btn-sm">
                                   <i class="bi bi-whatsapp"></i>
                              </a>
                              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                   target="_blank" rel="noopener noreferrer" class="btn btn-rs-outline btn-sm">
                                   <i class="bi bi-facebook"></i>
                              </a>
                              <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                   target="_blank" rel="noopener noreferrer" class="btn btn-rs-outline btn-sm">
                                   <i class="bi bi-twitter-x"></i>
                              </a>
                              <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                                   target="_blank" rel="noopener noreferrer" class="btn btn-rs-outline btn-sm">
                                   <i class="bi bi-linkedin"></i>
                              </a>
                         </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-lg-4">
                         <div class="position-sticky" style="top: calc(var(--rs-navbar-h) + 24px);">
                              <div class="rs-card mb-4" data-reveal>
                                   <div class="rs-card__icon"><i class="bi bi-chat-dots"></i></div>
                                   <h3 class="h6">Punya Proyek Digital?</h3>
                                   <p>Konsultasikan kebutuhan website atau sistem informasi Anda dengan tim kami.</p>
                                   <a href="{{ $whatsappUrl ?? route('contact') }}"
                                        @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                                        class="btn btn-rs-primary w-100">
                                        <i class="bi {{ !empty($whatsappUrl) ? 'bi-whatsapp' : 'bi-chat-dots' }}"></i>
                                        Konsultasi Gratis
                                   </a>
                              </div>

                              @if ($related->isNotEmpty())
                                   <div class="rs-card" data-reveal>
                                        <h3 class="h6 mb-3">Artikel Terkait</h3>
                                        @foreach ($related as $item)
                                             <a href="{{ route('blog.show', $item->slug) }}"
                                                  class="d-flex gap-3 mb-3 pb-3 text-decoration-none"
                                                  style="border-bottom:1px solid var(--rs-line);">
                                                  @if ($item->featured_image)
                                                       <img src="{{ \App\Support\Media::url($item->featured_image) }}"
                                                            alt="{{ $item->title }}"
                                                            style="width:64px;height:64px;object-fit:cover;border-radius:10px;flex-shrink:0;"
                                                            loading="lazy">
                                                  @endif
                                                  <div>
                                                       <strong
                                                            style="display:block;font-size:.88rem;color:var(--rs-ink);line-height:1.4;">
                                                            {{ \Illuminate\Support\Str::limit($item->title, 60) }}
                                                       </strong>
                                                       <span class="rs-text-muted" style="font-size:.76rem;">
                                                            {{ ($item->published_at ?? $item->created_at)?->translatedFormat('d M Y') }}
                                                       </span>
                                                  </div>
                                             </a>
                                        @endforeach
                                   </div>
                              @endif
                         </div>
                    </div>
               </div>
          </div>
     </section>

     @include('frontend.partials.cta')
@endsection
