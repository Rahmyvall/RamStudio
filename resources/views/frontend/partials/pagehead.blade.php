@php
     $crumbs = $breadcrumbs ?? [];
@endphp

<section class="rs-pagehead">
     <div class="rs-container">
          <div class="rs-pagehead__inner">
               @if (!empty($crumbs))
                    <nav aria-label="Breadcrumb">
                         <ol class="rs-breadcrumb">
                              <li><a href="{{ route('home') }}">Beranda</a></li>
                              @foreach ($crumbs as $crumb)
                                   <li>
                                        @if (!empty($crumb['url']))
                                             <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                                        @else
                                             <span aria-current="page">{{ $crumb['label'] }}</span>
                                        @endif
                                   </li>
                              @endforeach
                         </ol>
                    </nav>
               @endif

               @if (!empty($eyebrow))
                    <span class="rs-eyebrow"><i class="bi bi-stars"></i>{{ $eyebrow }}</span>
               @endif

               <h1>{{ $title ?? '' }}</h1>

               @if (!empty($subtitle))
                    <p>{{ $subtitle }}</p>
               @endif
          </div>
     </div>
</section>
