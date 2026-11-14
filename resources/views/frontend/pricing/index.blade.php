@extends('frontend.layouts.app')

@section('content')
     @include('frontend.partials.pagehead', [
         'title' => 'Paket Harga',
         'subtitle' => 'Investasi transparan untuk setiap tahap pertumbuhan bisnis Anda. Tanpa biaya tersembunyi.',
         'eyebrow' => 'Harga & Paket',
         'breadcrumbs' => [['label' => 'Paket Harga']],
     ])

     <section class="rs-section">
          <div class="rs-container">
               <div class="row g-4 justify-content-center">
                    @forelse ($packages as $index => $package)
                         <div class="col-md-6 col-lg-4">
                              <div class="rs-price-card {{ $package->is_popular ? 'rs-price-card--popular' : '' }}"
                                   data-reveal data-reveal-delay="{{ $index * 80 }}">
                                   @if ($package->is_popular)
                                        <span class="rs-price-card__badge">Paling Populer</span>
                                   @endif

                                   <h3>{{ $package->name }}</h3>
                                   <p class="rs-price-card__desc">{{ $package->description }}</p>

                                   <div class="rs-price-card__amount">
                                        @if ($package->price !== null)
                                             Rp {{ number_format((float) $package->price, 0, ',', '.') }}
                                             <small>{{ $package->price_label }}</small>
                                        @else
                                             <small>Hubungi kami</small>
                                        @endif
                                   </div>

                                   <ul class="rs-price-card__features">
                                        @forelse ($package->features as $feature)
                                             <li class="{{ $feature->is_included ? '' : 'is-excluded' }}">
                                                  <i
                                                       class="bi {{ $feature->is_included ? 'bi-check-circle-fill' : 'bi-x-circle' }}"></i>
                                                  <span>{{ $feature->feature }}</span>
                                             </li>
                                        @empty
                                             <li><i class="bi bi-info-circle"></i><span>Detail fitur akan segera
                                                       diperbarui.</span></li>
                                        @endforelse
                                   </ul>

                                   @php
                                        $packageWa = \App\Support\WhatsApp::packageLink(
                                            $whatsappNumber ?? null,
                                            $package->name,
                                        );
                                   @endphp
                                   <a href="{{ $packageWa ?? route('contact') . '?paket=' . $package->slug }}"
                                        @if ($packageWa) target="_blank" rel="noopener noreferrer" @endif
                                        class="btn {{ $package->is_popular ? 'btn-rs-primary' : 'btn-rs-outline' }} w-100">
                                        Pilih Paket
                                        <i class="bi {{ $packageWa ? 'bi-whatsapp' : 'bi-arrow-right' }}"></i>
                                   </a>
                              </div>
                         </div>
                    @empty
                         <div class="col-12">
                              <div class="rs-empty">
                                   <i class="bi bi-tags"></i>
                                   <h4>Belum ada paket harga</h4>
                                   <p>Paket harga akan tampil di sini setelah ditambahkan melalui dashboard admin.</p>
                              </div>
                         </div>
                    @endforelse
               </div>

               {{-- Comparison table --}}
               @if ($packages->isNotEmpty())
                    <div class="mt-5 pt-4" data-reveal>
                         <div class="rs-heading rs-heading--center">
                              <span class="rs-eyebrow"><i class="bi bi-table"></i>Perbandingan</span>
                              <h2>Bandingkan Paket</h2>
                              <p>Lihat perbedaan fitur setiap paket secara berdampingan.</p>
                         </div>

                         <div class="table-responsive rs-rounded-lg" style="border:1px solid var(--rs-line);">
                              <table class="table align-middle mb-0" style="min-width:640px;">
                                   <thead>
                                        <tr style="background:var(--rs-surface);">
                                             <th scope="col" style="padding:1rem 1.25rem;font-size:.85rem;">Fitur</th>
                                             @foreach ($packages as $package)
                                                  <th scope="col" class="text-center"
                                                       style="padding:1rem 1.25rem;font-size:.85rem;">
                                                       {{ $package->name }}
                                                       @if ($package->is_popular)
                                                            <span class="rs-chip rs-chip--accent d-block mt-1"
                                                                 style="font-size:.65rem;">Populer</span>
                                                       @endif
                                                  </th>
                                             @endforeach
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @php
                                             $allFeatures = $packages
                                                 ->flatMap(fn($p) => $p->features->pluck('feature'))
                                                 ->unique()
                                                 ->values();
                                        @endphp

                                        @forelse ($allFeatures as $featureName)
                                             <tr>
                                                  <td style="padding:.85rem 1.25rem;font-size:.9rem;font-weight:600;">
                                                       {{ $featureName }}
                                                  </td>
                                                  @foreach ($packages as $package)
                                                       @php
                                                            $match = $package->features->firstWhere(
                                                                'feature',
                                                                $featureName,
                                                            );
                                                       @endphp
                                                       <td class="text-center" style="padding:.85rem 1.25rem;">
                                                            @if ($match && $match->is_included)
                                                                 <i class="bi bi-check-circle-fill"
                                                                      style="color:var(--rs-blue-500);font-size:1.1rem;"></i>
                                                            @else
                                                                 <i class="bi bi-dash"
                                                                      style="color:var(--rs-muted);font-size:1.1rem;"></i>
                                                            @endif
                                                       </td>
                                                  @endforeach
                                             </tr>
                                        @empty
                                             <tr>
                                                  <td colspan="{{ $packages->count() + 1 }}"
                                                       class="text-center rs-text-muted"
                                                       style="padding:1.5rem;font-size:.9rem;">
                                                       Detail perbandingan fitur belum tersedia.
                                                  </td>
                                             </tr>
                                        @endforelse
                                   </tbody>
                              </table>
                         </div>
                    </div>
               @endif
          </div>
     </section>

     {{-- What's included --}}
     <section class="rs-section rs-section--surface">
          <div class="rs-container">
               <div class="rs-heading rs-heading--center" data-reveal>
                    <span class="rs-eyebrow"><i class="bi bi-check2-square"></i>Termasuk</span>
                    <h2>Standar Layanan di Setiap Paket</h2>
                    <p>Semua paket mencakup fondasi kualitas yang sama dari kami.</p>
               </div>

               <div class="row g-4">
                    @php
                         $included = [
                             [
                                 'icon' => 'bi bi-phone',
                                 'title' => 'Desain Responsif',
                                 'text' => 'Tampilan optimal di semua ukuran layar perangkat.',
                             ],
                             [
                                 'icon' => 'bi bi-search',
                                 'title' => 'SEO Dasar',
                                 'text' => 'Struktur HTML semantik dan meta tag yang siap diindeks.',
                             ],
                             [
                                 'icon' => 'bi bi-shield-check',
                                 'title' => 'Keamanan Standar',
                                 'text' => 'Praktik keamanan terbaik untuk melindungi data.',
                             ],
                             [
                                 'icon' => 'bi bi-speedometer2',
                                 'title' => 'Optimasi Performa',
                                 'text' => 'Waktu muat cepat untuk pengalaman pengguna yang baik.',
                             ],
                             [
                                 'icon' => 'bi bi-headset',
                                 'title' => 'Dukungan Teknis',
                                 'text' => 'Bantuan teknis selama masa garansi proyek.',
                             ],
                             [
                                 'icon' => 'bi bi-mortarboard',
                                 'title' => 'Panduan Penggunaan',
                                 'text' => 'Dokumentasi dan pelatihan singkat pengelolaan konten.',
                             ],
                         ];
                    @endphp

                    @foreach ($included as $index => $item)
                         <div class="col-md-6 col-lg-4">
                              <div class="rs-card h-100" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                   <div class="rs-card__icon"><i class="{{ $item['icon'] }}"></i></div>
                                   <h3 style="font-size:1.05rem;">{{ $item['title'] }}</h3>
                                   <p class="mb-0">{{ $item['text'] }}</p>
                              </div>
                         </div>
                    @endforeach
               </div>
          </div>
     </section>

     @include('frontend.partials.faq', [
         'faqs' => $faqs,
         'faqId' => 'pricing-faq',
         'faqTitle' => 'Pertanyaan Seputar Harga',
         'faqSubtitle' => 'Masih ragu memilih paket? Tim kami siap membantu.',
     ])

     @include('frontend.partials.cta', [
         'ctaTitle' => 'Butuh Paket Kustom?',
         'ctaText' =>
             'Setiap bisnis unik. Kami dapat menyusun penawaran khusus sesuai kebutuhan dan anggaran Anda.',
     ])
@endsection
