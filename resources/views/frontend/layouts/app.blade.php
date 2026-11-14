<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="theme-color" content="#071127">

     @include('frontend.partials.seo', ['seo' => $seo ?? []])

     {{-- Fonts --}}
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
          rel="stylesheet">

     {{-- Styles --}}
     <link rel="stylesheet" href="{{ asset('frontend-dev/vendors/bootstrap/bootstrap.min.css') }}">
     <link rel="stylesheet" href="{{ asset('frontend-dev/vendors/bootstrap-icons/bootstrap-icons.min.css') }}">
     <link rel="stylesheet" href="{{ asset('frontend-dev/css/ramstudio.css') }}">

     @stack('styles')
</head>

<body>
     <a href="#main-content" class="rs-skip">Lewati ke konten utama</a>

     @include('frontend.partials.navbar')

     <main id="main-content">
          @yield('content')
     </main>

     @include('frontend.partials.footer')

     {{-- Back to top --}}
     <button type="button" class="rs-totop" data-rs-totop aria-label="Kembali ke atas">
          <i class="bi bi-arrow-up"></i>
     </button>

     {{-- WhatsApp floating button --}}
     @if (!empty($whatsappUrl))
          <a href="{{ $whatsappUrl }}" class="rs-wa" target="_blank" rel="noopener noreferrer"
               aria-label="Konsultasi gratis via WhatsApp" title="Konsultasi gratis via WhatsApp">
               <i class="bi bi-whatsapp"></i>
          </a>
     @endif

     {{-- Image lightbox (dipakai galeri portofolio) --}}
     <div class="rs-lightbox" data-rs-lightbox hidden role="dialog" aria-modal="true" aria-label="Pratinjau foto">
          <button type="button" class="rs-lightbox__close" data-rs-lightbox-close aria-label="Tutup pratinjau">
               <i class="bi bi-x-lg"></i>
          </button>
          <button type="button" class="rs-lightbox__nav rs-lightbox__nav--prev" data-rs-lightbox-prev
               aria-label="Foto sebelumnya">
               <i class="bi bi-chevron-left"></i>
          </button>
          <figure class="rs-lightbox__figure">
               <img src="" alt="" data-rs-lightbox-image>
               <figcaption data-rs-lightbox-caption></figcaption>
          </figure>
          <button type="button" class="rs-lightbox__nav rs-lightbox__nav--next" data-rs-lightbox-next
               aria-label="Foto berikutnya">
               <i class="bi bi-chevron-right"></i>
          </button>
     </div>

     <script src="{{ asset('frontend-dev/vendors/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
     <script src="{{ asset('frontend-dev/js/ramstudio.js') }}" defer></script>

     @stack('scripts')
</body>

</html>
