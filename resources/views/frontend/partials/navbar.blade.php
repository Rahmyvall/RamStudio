@php
     $navItems = [
         ['label' => 'Beranda', 'route' => 'home'],
         ['label' => 'Tentang Kami', 'route' => 'about'],
         ['label' => 'Layanan', 'route' => 'services.index'],
         ['label' => 'Portofolio', 'route' => 'portfolios.index'],
         ['label' => 'Harga', 'route' => 'pricing'],
         ['label' => 'FAQ', 'route' => 'faqs.index'],
         ['label' => 'Blog', 'route' => 'blog.index'],
         ['label' => 'Kontak', 'route' => 'contact'],
     ];

     $isActive = function (string $route) {
         return request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route));
     };
@endphp

<header class="rs-navbar">
     {{-- Top utility bar --}}
     <div class="rs-topbar">
          <div class="rs-container">
               <div class="rs-topbar__inner">
                    <ul class="rs-topbar__list">
                         @if (!empty($site['email']))
                              <li>
                                   <a href="mailto:{{ $site['email'] }}">
                                        <i class="bi bi-envelope"></i>
                                        {{ $site['email'] }}
                                   </a>
                              </li>
                         @endif
                         @if (!empty($site['phone']))
                              <li>
                                   <a href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone']) }}">
                                        <i class="bi bi-telephone"></i>
                                        {{ $site['phone'] }}
                                   </a>
                              </li>
                         @endif
                    </ul>

                    <span class="rs-topbar__note">
                         <i class="bi bi-clock"></i>
                         Senin – Sabtu · 08.00 – 17.00 WIB
                    </span>
               </div>
          </div>
     </div>

     <div class="rs-container">
          <div class="rs-navbar__inner">
               {{-- Brand --}}
               <a href="{{ route('home') }}" class="rs-brand" aria-label="{{ $site['name'] ?? 'RAMStudio' }} — Beranda">
                    <span class="rs-brand__mark">
                         @if (!empty($site['logo']))
                              <img src="{{ $site['logo'] }}" alt="{{ $site['name'] ?? 'RAMStudio' }}">
                         @else
                              RS
                         @endif
                    </span>
                    <span class="rs-brand__text">
                         {{ $site['name'] ?? 'RAMStudio' }}
                         <small>{{ $site['tagline'] ?? 'Digital Studio' }}</small>
                    </span>
               </a>

               {{-- Desktop navigation --}}
               <nav aria-label="Navigasi utama">
                    <ul class="rs-nav">
                         @foreach ($navItems as $item)
                              <li>
                                   <a href="{{ route($item['route']) }}"
                                        class="rs-nav__link {{ $isActive($item['route']) ? 'is-active' : '' }}"
                                        @if ($isActive($item['route'])) aria-current="page" @endif>
                                        {{ $item['label'] }}
                                   </a>
                              </li>
                         @endforeach
                    </ul>
               </nav>

               {{-- Actions --}}
               <div class="rs-navbar__actions">
                    <a href="{{ $whatsappUrl ?? route('contact') }}"
                         @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                         class="btn btn-rs-primary btn-sm">
                         <i class="bi bi-whatsapp"></i>
                         Konsultasi Gratis
                    </a>

                    <button type="button" class="rs-navbar__toggle" data-rs-menu-toggle aria-expanded="false"
                         aria-controls="rs-mobile-menu" aria-label="Buka menu navigasi">
                         <i class="bi bi-list"></i>
                    </button>
               </div>
          </div>
     </div>

     {{-- Mobile menu --}}
     <div class="rs-mobile-menu" id="rs-mobile-menu" data-rs-menu>
          <div class="rs-container">
               <ul>
                    @foreach ($navItems as $item)
                         <li>
                              <a href="{{ route($item['route']) }}"
                                   class="{{ $isActive($item['route']) ? 'is-active' : '' }}">
                                   {{ $item['label'] }}
                                   <i class="bi bi-chevron-right"></i>
                              </a>
                         </li>
                    @endforeach
               </ul>
               <a href="{{ $whatsappUrl ?? route('contact') }}"
                    @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                    class="btn btn-rs-primary">
                    <i class="bi bi-whatsapp"></i>
                    Konsultasi Gratis
               </a>
          </div>
     </div>
</header>
