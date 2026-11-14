@extends('frontend.layouts.app')

@section('content')
     <section class="rs-hero" style="min-height:70vh;display:flex;align-items:center;">
          <div class="rs-container">
               <div class="text-center mx-auto" style="max-width:640px;">
                    <div class="mb-4">
                         <span class="rs-gradient-text"
                              style="font-family:var(--rs-font-display);font-size:clamp(5rem,16vw,9rem);font-weight:800;line-height:1;letter-spacing:-.05em;display:block;">
                              404
                         </span>
                    </div>

                    <h1 class="mb-3">Halaman Tidak Ditemukan</h1>
                    <p class="rs-hero__lead mx-auto mb-4">
                         Maaf, halaman yang Anda cari tidak tersedia, telah dipindahkan, atau alamatnya salah.
                    </p>

                    <div class="rs-hero__actions justify-content-center">
                         <a href="{{ route('home') }}" class="btn btn-rs-primary btn-lg">
                              <i class="bi bi-house-door"></i>
                              Kembali ke Beranda
                         </a>
                         <a href="{{ $whatsappUrl ?? route('contact') }}"
                              @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                              class="btn btn-rs-ghost-light btn-lg">
                              <i class="bi {{ !empty($whatsappUrl) ? 'bi-whatsapp' : 'bi-chat-dots' }}"></i>
                              Hubungi Kami
                         </a>
                    </div>

                    <div class="mt-5 pt-4" style="border-top:1px solid rgba(255,255,255,.1);">
                         <p class="mb-3" style="font-size:.85rem;color:rgba(255,255,255,.5);">
                              Atau jelajahi halaman berikut:
                         </p>
                         <div class="d-flex flex-wrap gap-2 justify-content-center">
                              <a href="{{ route('services.index') }}" class="btn btn-rs-ghost-light btn-sm">Layanan</a>
                              <a href="{{ route('portfolios.index') }}" class="btn btn-rs-ghost-light btn-sm">Portofolio</a>
                              <a href="{{ route('pricing') }}" class="btn btn-rs-ghost-light btn-sm">Paket Harga</a>
                              <a href="{{ route('blog.index') }}" class="btn btn-rs-ghost-light btn-sm">Blog</a>
                              <a href="{{ route('about') }}" class="btn btn-rs-ghost-light btn-sm">Tentang Kami</a>
                         </div>
                    </div>
               </div>
          </div>
     </section>
@endsection
