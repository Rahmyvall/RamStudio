@extends('frontend.layouts.app')

@section('content')
     @php
          $wa = $whatsappNumber ?? null;
          $prefillService = request('layanan');
          $prefillPackage = request('paket');
     @endphp

     @include('frontend.partials.pagehead', [
         'title' => 'Hubungi Kami',
         'subtitle' => 'Ceritakan kebutuhan Anda. Kami akan merespons dalam 1×24 jam kerja.',
         'eyebrow' => 'Kontak & Konsultasi',
         'breadcrumbs' => [['label' => 'Kontak']],
     ])

     <section class="rs-section">
          <div class="rs-container">
               <div class="row g-5">
                    {{-- Contact info --}}
                    <div class="col-lg-5">
                         <div class="rs-heading" data-reveal>
                              <span class="rs-eyebrow"><i class="bi bi-headset"></i>Mari Terhubung</span>
                              <h2>Kami Siap Membantu</h2>
                              <p>Pilih cara yang paling nyaman untuk Anda. Tim kami akan dengan senang hati berdiskusi.</p>
                         </div>

                         <div class="rs-channels" data-reveal>
                              @if (!empty($site['email']))
                                   <div class="rs-channel">
                                        <div class="rs-channel__icon"><i class="bi bi-envelope"></i></div>
                                        <div class="rs-channel__body">
                                             <strong>Email</strong>
                                             <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>
                                        </div>
                                        <i class="bi bi-arrow-up-right rs-channel__arrow"></i>
                                   </div>
                              @endif

                              @if (!empty($site['phone']))
                                   <div class="rs-channel">
                                        <div class="rs-channel__icon"><i class="bi bi-telephone"></i></div>
                                        <div class="rs-channel__body">
                                             <strong>Telepon</strong>
                                             <a
                                                  href="tel:{{ preg_replace('/[^0-9+]/', '', $site['phone']) }}">{{ $site['phone'] }}</a>
                                        </div>
                                        <i class="bi bi-arrow-up-right rs-channel__arrow"></i>
                                   </div>
                              @endif

                              @if ($wa)
                                   <div class="rs-channel">
                                        <div class="rs-channel__icon rs-channel__icon--wa"><i class="bi bi-whatsapp"></i>
                                        </div>
                                        <div class="rs-channel__body">
                                             <strong>WhatsApp</strong>
                                             <a href="{{ $whatsappUrl ?? 'https://wa.me/' . $wa }}" target="_blank"
                                                  rel="noopener noreferrer">Chat langsung</a>
                                        </div>
                                        <i class="bi bi-arrow-up-right rs-channel__arrow"></i>
                                   </div>
                              @endif

                              @if (!empty($site['address']))
                                   <div class="rs-channel">
                                        <div class="rs-channel__icon"><i class="bi bi-geo-alt"></i></div>
                                        <div class="rs-channel__body">
                                             <strong>Lokasi</strong>
                                             <a href="https://maps.google.com/?q={{ urlencode($site['address']) }}"
                                                  target="_blank" rel="noopener noreferrer">{{ $site['address'] }}</a>
                                        </div>
                                        <i class="bi bi-arrow-up-right rs-channel__arrow"></i>
                                   </div>
                              @endif
                         </div>

                         {{-- WhatsApp quick consultation --}}
                         @if (!empty($whatsappUrl))
                              <div class="rs-wa-panel mt-4" data-reveal>
                                   <div class="rs-wa-panel__head">
                                        <div class="rs-wa-panel__icon"><i class="bi bi-whatsapp"></i></div>
                                        <div>
                                             <h3>Konsultasi Langsung via WhatsApp</h3>
                                             <p>Respons lebih cepat, tanpa perlu mengisi formulir.</p>
                                        </div>
                                   </div>
                                   <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="btn w-100">
                                        <i class="bi bi-whatsapp"></i>
                                        Mulai Chat Sekarang
                                   </a>
                              </div>
                         @endif

                         {{-- Social --}}
                         @if ($socialLinks->isNotEmpty())
                              <div class="mt-4" data-reveal>
                                   <span class="rs-fieldset__legend mb-2">
                                        <i class="bi bi-share"></i>Media Sosial
                                   </span>
                                   <div class="rs-social-row">
                                        @foreach ($socialLinks as $link)
                                             @php
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
                                                  ];
                                                  $icon =
                                                      $link->icon && str_contains($link->icon, 'bi-')
                                                          ? $link->icon
                                                          : 'bi ' .
                                                              ($map[strtolower($link->platform ?? '')] ??
                                                                  'bi-link-45deg');
                                                  $isWhatsApp = strtolower($link->platform ?? '') === 'whatsapp';
                                                  $socialUrl =
                                                      $isWhatsApp && !empty($whatsappUrl) ? $whatsappUrl : $link->url;
                                             @endphp
                                             <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"
                                                  title="{{ $link->platform }}">
                                                  <i class="{{ $icon }}"></i>
                                                  {{ $link->platform }}
                                             </a>
                                        @endforeach
                                   </div>
                              </div>
                         @endif

                         {{-- Response time --}}
                         <div class="rs-sla" data-reveal>
                              <div class="rs-sla__item">
                                   <i class="bi bi-clock-history"></i>
                                   <div>
                                        <strong>&lt; 24 Jam</strong>
                                        <span>Waktu Respons</span>
                                   </div>
                              </div>
                              <div class="rs-sla__item">
                                   <i class="bi bi-calendar-week"></i>
                                   <div>
                                        <strong>Sen – Sab</strong>
                                        <span>Hari Kerja</span>
                                   </div>
                              </div>
                              <div class="rs-sla__item">
                                   <i class="bi bi-shield-check"></i>
                                   <div>
                                        <strong>Gratis</strong>
                                        <span>Konsultasi Awal</span>
                                   </div>
                              </div>
                         </div>
                    </div>

                    {{-- Form --}}
                    <div class="col-lg-7">
                         <div class="rs-form-panel" data-reveal>
                              <div class="rs-form-panel__head">
                                   <h2>Formulir Konsultasi</h2>
                                   <p>Isi formulir di bawah ini dan tim kami akan menghubungi Anda.</p>
                              </div>

                              <div class="rs-form-panel__body">
                                   <div data-rs-form-alert></div>

                                   <form class="rs-form" data-rs-inquiry-form data-endpoint="{{ url('/api/v1/inquiries') }}"
                                        novalidate>
                                        @csrf

                                        <div class="rs-fieldset">
                                             <span class="rs-fieldset__legend">
                                                  <i class="bi bi-person"></i>Data Diri
                                             </span>

                                             <div class="row g-3">
                                                  <div class="col-md-6">
                                                       <label for="name" class="form-label">Nama Lengkap<span
                                                                 class="req">*</span></label>
                                                       <input type="text" class="form-control" id="name"
                                                            name="name" placeholder="Nama Anda" required maxlength="150">
                                                       <div class="invalid-feedback"></div>
                                                  </div>

                                                  <div class="col-md-6">
                                                       <label for="email" class="form-label">Email<span
                                                                 class="req">*</span></label>
                                                       <input type="email" class="form-control" id="email"
                                                            name="email" placeholder="nama@email.com" required
                                                            maxlength="190">
                                                       <div class="invalid-feedback"></div>
                                                  </div>

                                                  <div class="col-md-6">
                                                       <label for="phone" class="form-label">Nomor Telepon</label>
                                                       <input type="text" class="form-control" id="phone"
                                                            name="phone" placeholder="08xxxxxxxxxx" maxlength="40">
                                                       <div class="invalid-feedback"></div>
                                                  </div>

                                                  <div class="col-md-6">
                                                       <label for="company" class="form-label">Perusahaan /
                                                            Instansi</label>
                                                       <input type="text" class="form-control" id="company"
                                                            name="company" placeholder="Nama perusahaan" maxlength="180">
                                                       <div class="invalid-feedback"></div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div class="rs-fieldset">
                                             <span class="rs-fieldset__legend">
                                                  <i class="bi bi-clipboard-data"></i>Detail Kebutuhan
                                             </span>

                                             <div class="row g-3">
                                                  <div class="col-md-6">
                                                       <label for="subject" class="form-label">Subjek</label>
                                                       <input type="text" class="form-control" id="subject"
                                                            name="subject"
                                                            placeholder="Contoh: Pembuatan website company profile"
                                                            maxlength="200">
                                                       <div class="invalid-feedback"></div>
                                                  </div>

                                                  <div class="col-md-6">
                                                       <label for="budget" class="form-label">Perkiraan Anggaran</label>
                                                       <select class="form-select" id="budget" name="budget">
                                                            <option value="">Pilih rentang anggaran</option>
                                                            <option value="< 5 juta">&lt; Rp 5 juta</option>
                                                            <option value="5 - 10 juta">Rp 5 – 10 juta</option>
                                                            <option value="10 - 25 juta">Rp 10 – 25 juta</option>
                                                            <option value="25 - 50 juta">Rp 25 – 50 juta</option>
                                                            <option value="> 50 juta">&gt; Rp 50 juta</option>
                                                            <option value="Belum ditentukan">Belum ditentukan</option>
                                                       </select>
                                                       <div class="invalid-feedback"></div>
                                                  </div>

                                                  <div class="col-12">
                                                       <label for="services" class="form-label">Layanan yang
                                                            Diminati</label>
                                                       <select class="form-select" id="services" name="services[]"
                                                            multiple size="4">
                                                            @foreach ($services as $service)
                                                                 <option value="{{ $service->id }}"
                                                                      {{ $prefillService === $service->slug ? 'selected' : '' }}>
                                                                      {{ $service->name }}
                                                                 </option>
                                                            @endforeach
                                                       </select>
                                                       <div class="form-text">Tahan Ctrl (Windows) atau Cmd (Mac) untuk
                                                            memilih
                                                            lebih dari satu.</div>
                                                       <div class="invalid-feedback"></div>
                                                  </div>

                                                  <div class="col-12">
                                                       <label for="message" class="form-label">Pesan<span
                                                                 class="req">*</span></label>
                                                       <textarea class="form-control" id="message" name="message" rows="5" required
                                                            placeholder="Ceritakan kebutuhan, tujuan, dan gambaran proyek Anda..."></textarea>
                                                       <div class="invalid-feedback"></div>
                                                  </div>

                                                  @if ($prefillPackage)
                                                       <div class="col-12">
                                                            <div class="rs-alert rs-alert--info mb-0">
                                                                 <i class="bi bi-info-circle"></i>
                                                                 <div>
                                                                      Anda tertarik dengan paket
                                                                      <strong>{{ $prefillPackage }}</strong>. Sebutkan pada
                                                                      pesan Anda agar kami dapat menyesuaikan penawaran.
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  @endif
                                             </div>
                                        </div>

                                        <div class="rs-form__submit">
                                             <button type="submit" class="btn btn-rs-primary btn-lg w-100">
                                                  <i class="bi bi-send"></i>
                                                  Kirim Pesan
                                             </button>
                                             <p class="rs-form__note">
                                                  Dengan mengirim formulir ini, Anda menyetujui data digunakan untuk
                                                  keperluan komunikasi terkait permintaan Anda.
                                             </p>
                                        </div>
                                   </form>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </section>

     {{-- ============================== LOCATION MAP ============================== --}}
     @if (!empty($site['map_embed']))
          <section class="rs-section rs-section--surface" id="lokasi">
               <div class="rs-container">
                    <div class="rs-heading rs-heading--center" data-reveal>
                         <span class="rs-eyebrow"><i class="bi bi-geo-alt"></i>Lokasi Kami</span>
                         <h2>Temukan Kami di Peta</h2>
                         <p>
                              @if (!empty($site['address']))
                                   {{ $site['address'] }}
                              @else
                                   Kunjungi kantor kami atau hubungi terlebih dahulu untuk membuat janji pertemuan.
                              @endif
                         </p>
                    </div>

                    <div class="rs-map-wrap" data-reveal>
                         <iframe src="{{ $site['map_embed'] }}" title="Lokasi {{ $site['name'] ?? 'RAMStudio' }}"
                              loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>

                    <div class="rs-map-actions" data-reveal>
                         @if (!empty($site['map_link']))
                              <a href="{{ $site['map_link'] }}" target="_blank" rel="noopener noreferrer"
                                   class="btn btn-rs-primary">
                                   <i class="bi bi-geo-alt-fill"></i>
                                   Buka di Google Maps
                              </a>
                         @endif
                         @if (!empty($site['address']))
                              <a href="https://www.google.com/maps/dir/?api=1&destination={{ rawurlencode($site['address']) }}"
                                   target="_blank" rel="noopener noreferrer" class="btn btn-rs-outline">
                                   <i class="bi bi-signpost-2"></i>
                                   Petunjuk Arah
                              </a>
                         @endif
                    </div>
               </div>
          </section>
     @endif

     @include('frontend.partials.faq', [
         'faqs' => $faqs,
         'faqId' => 'contact-faq',
         'faqTitle' => 'Pertanyaan Umum',
         'faqSubtitle' => 'Jawaban singkat untuk pertanyaan yang sering diajukan.',
     ])
@endsection
