@php
     // Error views are rendered outside the frontend controller, so the shared
     // site data is not available. Resolve the WhatsApp link defensively.
     try {
         $waNumber = \App\Support\WhatsApp::number(
             \App\Models\AboutSocialLink::query()
                 ->where('platform', 'WhatsApp')
                 ->where('is_active', true)
                 ->value('url'),
             \App\Models\Setting::get('contact_phone'),
         );
         $waUrl = \App\Support\WhatsApp::link($waNumber);
     } catch (\Throwable $e) {
         $waUrl = null;
     }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="robots" content="noindex, follow">
     <title>404 — Halaman Tidak Ditemukan | RAMStudio</title>
     <meta name="description" content="Halaman yang Anda cari tidak tersedia atau telah dipindahkan.">

     <link rel="icon" type="image/svg+xml" href="{{ asset('frontend-dev/images/favicon.svg') }}">
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
          rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('frontend-dev/vendors/bootstrap/bootstrap.min.css') }}">
     <link rel="stylesheet" href="{{ asset('frontend-dev/vendors/bootstrap-icons/bootstrap-icons.min.css') }}">
     <link rel="stylesheet" href="{{ asset('frontend-dev/css/ramstudio.css') }}">
</head>

<body>
     <section class="rs-hero" style="min-height:100vh;display:flex;align-items:center;">
          <div class="rs-container">
               <div class="text-center mx-auto" style="max-width:640px;">
                    <a href="{{ url('/') }}" class="rs-brand justify-content-center mb-5" style="color:#fff;">
                         <span class="rs-brand__mark">RS</span>
                         <span class="rs-brand__text" style="color:#fff;">
                              RAMStudio
                              <small style="color:rgba(255,255,255,.45);">Digital Studio</small>
                         </span>
                    </a>

                    <span class="rs-gradient-text"
                         style="font-family:var(--rs-font-display);font-size:clamp(5rem,16vw,9rem);font-weight:800;line-height:1;letter-spacing:-.05em;display:block;margin-bottom:1rem;">
                         404
                    </span>

                    <h1 class="mb-3">Halaman Tidak Ditemukan</h1>
                    <p class="rs-hero__lead mx-auto mb-4">
                         Maaf, halaman yang Anda cari tidak tersedia, telah dipindahkan, atau alamatnya salah.
                    </p>

                    <div class="rs-hero__actions justify-content-center">
                         <a href="{{ url('/') }}" class="btn btn-rs-primary btn-lg">
                              <i class="bi bi-house-door"></i>
                              Kembali ke Beranda
                         </a>
                         <a href="{{ $waUrl ?? url('/kontak') }}"
                              @if ($waUrl) target="_blank" rel="noopener noreferrer" @endif
                              class="btn btn-rs-ghost-light btn-lg">
                              <i class="bi {{ $waUrl ? 'bi-whatsapp' : 'bi-chat-dots' }}"></i>
                              Hubungi Kami
                         </a>
                    </div>

                    <div class="mt-5 pt-4" style="border-top:1px solid rgba(255,255,255,.1);">
                         <p class="mb-3" style="font-size:.85rem;color:rgba(255,255,255,.5);">
                              Atau jelajahi halaman berikut:
                         </p>
                         <div class="d-flex flex-wrap gap-2 justify-content-center">
                              <a href="{{ url('/layanan') }}" class="btn btn-rs-ghost-light btn-sm">Layanan</a>
                              <a href="{{ url('/portofolio') }}" class="btn btn-rs-ghost-light btn-sm">Portofolio</a>
                              <a href="{{ url('/harga') }}" class="btn btn-rs-ghost-light btn-sm">Paket Harga</a>
                              <a href="{{ url('/blog') }}" class="btn btn-rs-ghost-light btn-sm">Blog</a>
                              <a href="{{ url('/tentang-kami') }}" class="btn btn-rs-ghost-light btn-sm">Tentang
                                   Kami</a>
                         </div>
                    </div>
               </div>
          </div>
     </section>
</body>

</html>
