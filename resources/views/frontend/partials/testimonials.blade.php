{{--
     Testimoni — auto-slider (marquee) horizontal.

     Kartu dirender server-side agar tidak ada flash kosong, lalu JavaScript
     mengambil data terbaru langsung dari REST API
     (`GET /api/v1/testimonials?is_published=1`) dan membangun ulang slider.
--}}
@php
     $testimonials = $testimonials ?? collect();
     $hasTestimonials = $testimonials->isNotEmpty();
@endphp

<section class="rs-section rs-section--testimonials" id="testimoni" @if (!$hasTestimonials) hidden @endif>
     <div class="rs-container">
          <div class="rs-heading rs-heading--center" data-reveal>
               <span class="rs-eyebrow"><i class="bi bi-chat-quote"></i>Testimoni</span>
               <h2>Apa Kata Klien Kami</h2>
               <p>Kepercayaan klien adalah fondasi dari setiap proyek yang kami kerjakan.</p>
          </div>
     </div>

     <div class="rs-marquee" data-rs-testimonials
          data-endpoint="{{ url('/api/v1/testimonials') }}?is_published=1&amp;per_page=100&amp;sort=id&amp;order=asc"
          data-speed="46" role="region" aria-roledescription="carousel" aria-label="Testimoni klien">
          <div class="rs-marquee__viewport" data-rs-marquee-viewport tabindex="0"
               aria-label="Geser untuk melihat testimoni lain">
               <div class="rs-marquee__track" data-rs-marquee-track>
                    <div class="rs-marquee__group" data-rs-marquee-group>
                         @foreach ($testimonials as $testimonial)
                              <div class="rs-marquee__card">
                                   <div class="rs-quote">
                                        <div class="rs-quote__stars">
                                             @for ($i = 1; $i <= 5; $i++)
                                                  <i
                                                       class="bi {{ $i <= ($testimonial->rating ?? 5) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                             @endfor
                                        </div>
                                        <p class="rs-quote__text">
                                             &ldquo;{{ \Illuminate\Support\Str::limit(strip_tags((string) $testimonial->message), 220) }}&rdquo;
                                        </p>
                                        <div class="rs-quote__author">
                                             @if ($testimonial->client_photo)
                                                  <img src="{{ \App\Support\Media::url($testimonial->client_photo) }}"
                                                       alt="{{ $testimonial->client_name }}" class="rs-quote__avatar"
                                                       loading="lazy">
                                             @else
                                                  <div class="rs-quote__avatar">
                                                       {{ strtoupper(\Illuminate\Support\Str::substr($testimonial->client_name, 0, 1)) }}
                                                  </div>
                                             @endif
                                             <div>
                                                  <strong>{{ $testimonial->client_name }}</strong>
                                                  <span>
                                                       {{ collect([$testimonial->client_position, $testimonial->company_name])->filter()->implode(' · ') }}
                                                  </span>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         @endforeach
                    </div>
               </div>
          </div>

          <div class="rs-marquee__controls" data-rs-marquee-controls @if (!$hasTestimonials) hidden @endif>
               <button type="button" class="rs-marquee__nav" data-rs-marquee-prev aria-label="Testimoni sebelumnya">
                    <i class="bi bi-arrow-left"></i>
               </button>
               <span class="rs-marquee__hint"><i class="bi bi-hand-index"></i> Geser kartu atau gunakan
                    tombol</span>
               <button type="button" class="rs-marquee__nav" data-rs-marquee-next aria-label="Testimoni berikutnya">
                    <i class="bi bi-arrow-right"></i>
               </button>
          </div>
     </div>
</section>
