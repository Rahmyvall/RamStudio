@php
     $year = now()->year;
     $siteName = $site['name'] ?? 'RAMStudio';
     $wa = $whatsappNumber ?? null;
@endphp

<footer class="rs-footer">
     <div class="rs-container rs-footer__inner">
          <div class="row g-4 g-lg-5">
               {{-- Brand + about --}}
               <div class="col-lg-4">
                    <a href="{{ route('home') }}" class="rs-brand">
                         <span class="rs-brand__mark">
                              @if (!empty($site['logo']))
                                   <img src="{{ $site['logo'] }}" alt="{{ $siteName }}">
                              @else
                                   RS
                              @endif
                         </span>
                         <span class="rs-brand__text">
                              {{ $siteName }}
                              <small>{{ $site['tagline'] ?? 'Digital Studio' }}</small>
                         </span>
                    </a>

                    <p class="rs-footer__about">
                         {{ \Illuminate\Support\Str::limit(strip_tags($site['description'] ?? 'Studio digital yang membantu bisnis tumbuh melalui website, sistem informasi, dan aplikasi web yang profesional.'), 180) }}
                    </p>

                    <div class="rs-footer__social">
                         @forelse ($socialLinks as $link)
                              @php
                                   $isWhatsApp = strtolower($link->platform ?? '') === 'whatsapp';
                                   $socialUrl = $isWhatsApp && !empty($whatsappUrl) ? $whatsappUrl : $link->url;
                              @endphp
                              <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="{{ $link->platform }}" title="{{ $link->platform }}">
                                   @php
                                        $icon = $link->icon;
                                        if (!$icon || !str_contains($icon, 'bi-')) {
                                            $map = [
                                                'instagram' => 'bi-instagram',
                                                'linkedin' => 'bi-linkedin',
                                                'whatsapp' => 'bi-whatsapp',
                                                'facebook' => 'bi-facebook',
                                                'twitter' => 'bi-twitter-x',
                                                'x' => 'bi-twitter-x',
                                                'youtube' => 'bi-youtube',
                                                'github' => 'bi-github',
                                                'tiktok' => 'bi-tiktok',
                                                'email' => 'bi-envelope',
                                            ];
                                            $key = strtolower($link->platform ?? '');
                                            $icon = 'bi ' . ($map[$key] ?? 'bi-link-45deg');
                                        }
                                   @endphp
                                   <i class="{{ $icon }}"></i>
                              </a>
                         @empty
                              <a href="{{ route('contact') }}" aria-label="Kontak">
                                   <i class="bi bi-envelope"></i>
                              </a>
                         @endforelse
                    </div>
               </div>

               {{-- Navigation --}}
               <div class="col-6 col-lg-2">
                    <h5>Perusahaan</h5>
                    <ul class="rs-footer__links">
                         <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                         <li><a href="{{ route('services.index') }}">Layanan</a></li>
                         <li><a href="{{ route('portfolios.index') }}">Portofolio</a></li>
                         <li><a href="{{ route('faqs.index') }}">FAQ</a></li>
                         <li><a href="{{ route('blog.index') }}">Blog</a></li>
                         <li><a href="{{ route('contact') }}">Kontak</a></li>
                    </ul>
               </div>

               {{-- Services --}}
               <div class="col-6 col-lg-3">
                    <h5>Layanan</h5>
                    <ul class="rs-footer__links">
                         @forelse ($navServices as $service)
                              <li>
                                   <a href="{{ route('services.show', $service->slug) }}">{{ $service->name }}</a>
                              </li>
                         @empty
                              <li><a href="{{ route('services.index') }}">Lihat semua layanan</a></li>
                         @endforelse
                    </ul>
               </div>

               {{-- Contact --}}
               <div class="col-lg-3">
                    <h5>Hubungi Kami</h5>
                    <ul class="rs-footer__links">
                         @if (!empty($site['address']))
                              <li>
                                   <a href="https://maps.google.com/?q={{ urlencode($site['address']) }}"
                                        target="_blank" rel="noopener noreferrer">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $site['address'] }}
                                   </a>
                              </li>
                         @endif
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
                         @if ($wa)
                              <li>
                                   <a href="{{ $whatsappUrl ?? 'https://wa.me/+6285664206273' . $wa }}" target="_blank"
                                        rel="noopener noreferrer">
                                        <i class="bi bi-whatsapp"></i>
                                        WhatsApp
                                   </a>
                              </li>
                         @endif
                    </ul>
               </div>
          </div>

          <div class="rs-footer__bottom">
               <span>&copy; {{ $year }} {{ $siteName }}. Seluruh hak cipta dilindungi.</span>
               <span>
                    <a href="{{ route('pricing') }}">Paket Harga</a>
                    <span class="mx-2 opacity-50">·</span>
                    <a href="{{ $whatsappUrl ?? route('contact') }}"
                         @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif>Konsultasi</a>
               </span>
          </div>
     </div>
</footer>
