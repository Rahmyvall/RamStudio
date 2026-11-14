@extends('frontend.layouts.app')

@section('content')
     @include('frontend.partials.pagehead', [
         'title' => $page->title,
         'eyebrow' => 'Halaman',
         'breadcrumbs' => [['label' => $page->title]],
     ])

     <section class="rs-section">
          <div class="rs-container">
               <div class="row justify-content-center">
                    <div class="col-lg-9">
                         <article class="rs-prose" data-reveal>
                              @if ($page->content)
                                   {!! $page->content !!}
                              @else
                                   <p class="rs-text-muted">Konten halaman ini belum tersedia.</p>
                              @endif
                         </article>
                    </div>
               </div>
          </div>
     </section>

     @include('frontend.partials.cta')
@endsection
