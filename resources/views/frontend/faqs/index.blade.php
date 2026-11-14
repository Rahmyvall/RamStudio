@extends('frontend.layouts.app')

@section('content')
     @include('frontend.partials.pagehead', [
         'title' => 'Pertanyaan Umum',
         'subtitle' =>
             'Semua jawaban atas pertanyaan yang paling sering diajukan klien, dikelola langsung oleh tim ' .
             ($site['name'] ?? 'RAMStudio') .
             '.',
         'eyebrow' => 'Pusat Bantuan',
         'breadcrumbs' => [['label' => 'FAQ']],
     ])

     <section class="rs-section" data-rs-faq-root>
          <div class="rs-container">
               {{-- Highlight strip --}}
               <div class="rs-faqc-bar" data-reveal>
                    <div class="rs-faqc-bar__item">
                         <i class="bi bi-collection"></i>
                         <div>
                              <strong>{{ $faqTotal }} Pertanyaan Terjawab</strong>
                              <span>Diperbarui langsung dari dashboard admin agar selalu akurat.</span>
                         </div>
                    </div>
                    <div class="rs-faqc-bar__item">
                         <i class="bi bi-search"></i>
                         <div>
                              <strong>Pencarian Instan</strong>
                              <span>Ketik kata kunci untuk menyaring pertanyaan dalam sekejap.</span>
                         </div>
                    </div>
                    <div class="rs-faqc-bar__item">
                         <i class="bi bi-headset"></i>
                         <div>
                              <strong>Dukungan Manusia</strong>
                              <span>Belum terjawab? Tim kami siap membantu secara langsung.</span>
                         </div>
                    </div>
               </div>

               <div class="row g-5">
                    {{-- ------------------------------ ASIDE ------------------------------ --}}
                    <div class="col-lg-4">
                         <div class="rs-faqc-aside">
                              <div class="rs-faqc-count" data-reveal>
                                   <strong data-rs-faq-count>{{ $faqTotal }}</strong>
                                   <span>Pertanyaan Tampil</span>
                              </div>

                              <div class="rs-faqc-help" data-reveal data-reveal-delay="60">
                                   <div class="rs-faqc-help__icon"><i class="bi bi-chat-dots"></i></div>
                                   <h3>Masih Punya Pertanyaan?</h3>
                                   <p>Ceritakan kebutuhan Anda dan kami akan memberi rekomendasi terbaik, tanpa
                                        biaya konsultasi awal.</p>

                                   <a href="{{ $faqHelpUrl }}"
                                        @if ($faqHelpExternal) target="_blank" rel="noopener noreferrer" @endif
                                        class="btn btn-rs-primary w-100">
                                        <i class="bi {{ $faqHelpExternal ? 'bi-whatsapp' : 'bi-envelope' }}"></i>
                                        {{ $faqHelpExternal ? 'Chat via WhatsApp' : 'Hubungi Kami' }}
                                   </a>

                                   <ul class="rs-faqc-meta">
                                        <li><i class="bi bi-clock-history"></i>Respons &lt; 24 jam kerja</li>
                                        <li><i class="bi bi-calendar-week"></i>Senin – Sabtu, 08.00 – 17.00 WIB</li>
                                        <li><i class="bi bi-shield-check"></i>Konsultasi awal 100% gratis</li>
                                   </ul>

                                   <a href="{{ route('services.index') }}" class="rs-link-arrow mt-3"
                                        style="color:var(--rs-blue-200);">
                                        Jelajahi layanan kami
                                        <i class="bi bi-arrow-right"></i>
                                   </a>
                              </div>
                         </div>
                    </div>

                    {{-- ---------------------------- MAIN COLUMN ---------------------------- --}}
                    <div class="col-lg-8">
                         @if ($faqs->isNotEmpty())
                              {{-- Toolbar --}}
                              <div class="rs-faqc-toolbar" data-reveal>
                                   <div class="rs-faqc-search">
                                        <i class="bi bi-search"></i>
                                        <input type="search" data-rs-faq-search
                                             placeholder="Cari pertanyaan, mis. harga, revisi, hosting…"
                                             aria-label="Cari pertanyaan umum" autocomplete="off">
                                        <button type="button" class="rs-faqc-search__clear" data-rs-faq-clear
                                             aria-label="Hapus pencarian">
                                             <i class="bi bi-x-lg"></i>
                                        </button>
                                   </div>

                                   <div class="rs-faqc-toolbar__actions">
                                        <span class="rs-faqc-toolbar__count">
                                             <strong data-rs-faq-count>{{ $faqTotal }}</strong> dari
                                             {{ $faqTotal }} pertanyaan
                                        </span>

                                        <button type="button" class="btn btn-rs-outline" data-rs-faq-toggle-all="expand">
                                             <i class="bi bi-arrows-expand"></i>
                                             Buka Semua
                                        </button>
                                        <button type="button" class="btn btn-rs-outline" data-rs-faq-toggle-all="collapse">
                                             <i class="bi bi-arrows-collapse"></i>
                                             Tutup Semua
                                        </button>
                                   </div>
                              </div>

                              {{-- Accordion --}}
                              <div class="accordion rs-accordion rs-faq-list" id="faq-page-accordion">
                                   @foreach ($faqs as $index => $faq)
                                        <div class="accordion-item" data-rs-faq-item
                                             data-rs-faq-text="{{ $faq->question }} {{ strip_tags($faq->answer) }}"
                                             data-reveal data-reveal-delay="{{ min($index, 6) * 50 }}">
                                             <h3 class="accordion-header" id="faq-page-heading-{{ $faq->id }}">
                                                  <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                                       type="button" data-bs-toggle="collapse"
                                                       data-bs-target="#faq-page-collapse-{{ $faq->id }}"
                                                       aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                                       aria-controls="faq-page-collapse-{{ $faq->id }}">
                                                       <span>{{ $faq->question }}</span>
                                                  </button>
                                             </h3>
                                             <div id="faq-page-collapse-{{ $faq->id }}"
                                                  class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                                  aria-labelledby="faq-page-heading-{{ $faq->id }}">
                                                  <div class="accordion-body">
                                                       {!! nl2br(e($faq->answer)) !!}
                                                  </div>
                                             </div>
                                        </div>
                                   @endforeach
                              </div>

                              {{-- No search result --}}
                              <div class="rs-faq-empty" data-rs-faq-empty hidden>
                                   <i class="bi bi-search"></i>
                                   <h4>Pertanyaan tidak ditemukan</h4>
                                   <p>Coba gunakan kata kunci lain, atau tanyakan langsung kepada tim kami.</p>
                                   <button type="button" class="btn btn-rs-outline btn-sm" data-rs-faq-clear>
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        Reset Pencarian
                                   </button>
                              </div>
                         @else
                              <div class="rs-empty" data-reveal>
                                   <i class="bi bi-question-circle"></i>
                                   <h4>Belum ada pertanyaan umum</h4>
                                   <p>Daftar FAQ akan tampil di sini setelah ditambahkan melalui dashboard admin.</p>
                                   <a href="{{ route('contact') }}" class="btn btn-rs-primary mt-3">
                                        <i class="bi bi-chat-dots"></i>
                                        Tanyakan Langsung
                                   </a>
                              </div>
                         @endif

                         {{-- Closing note --}}
                         @if ($faqs->isNotEmpty())
                              <div class="rs-faqc-note mt-4" data-reveal>
                                   <i class="bi bi-info-circle"></i>
                                   <div>
                                        <strong>Tidak menemukan jawaban yang Anda cari?</strong>
                                        Kirim pertanyaan melalui halaman
                                        <a href="{{ route('contact') }}">Kontak</a>
                                        atau chat WhatsApp kami. Pertanyaan baru yang sering muncul akan kami
                                        tambahkan ke halaman ini.
                                   </div>
                              </div>
                         @endif
                    </div>
               </div>
          </div>
     </section>

     @include('frontend.partials.cta', [
         'ctaTitle' => 'Butuh Jawaban yang Lebih Spesifik?',
         'ctaText' =>
             'Setiap proyek memiliki kebutuhan berbeda. Diskusikan detailnya bersama kami dan dapatkan rekomendasi yang tepat.',
         'ctaPrimary' => 'Konsultasi Gratis',
     ])
@endsection
