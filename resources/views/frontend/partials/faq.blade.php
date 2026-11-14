@php
     $faqItems = $faqs ?? collect();
     $faqTitle = $faqTitle ?? 'Pertanyaan yang Sering Diajukan';
     $faqSubtitle = $faqSubtitle ?? 'Belum menemukan jawaban? Hubungi tim kami langsung.';
     $faqId = $faqId ?? 'faq';
     $faqTotal = $faqTotal ?? $faqItems->count();
     $hasMoreFaqs = $faqTotal > $faqItems->count();
@endphp

@if ($faqItems->isNotEmpty())
     <section class="rs-section rs-section--surface" id="{{ $faqId }}">
          <div class="rs-container">
               <div class="row g-5">
                    <div class="col-lg-4">
                         <div class="rs-heading mb-0" data-reveal>
                              <span class="rs-eyebrow"><i class="bi bi-question-circle"></i>FAQ</span>
                              <h2>{{ $faqTitle }}</h2>
                              <p>{{ $faqSubtitle }}</p>
                              <a href="{{ $whatsappUrl ?? route('contact') }}"
                                   @if (!empty($whatsappUrl)) target="_blank" rel="noopener noreferrer" @endif
                                   class="rs-link-arrow mt-3">
                                   Hubungi kami
                                   <i class="bi bi-arrow-right"></i>
                              </a>

                              @if ($hasMoreFaqs)
                                   <a href="{{ route('faqs.index') }}" class="rs-link-arrow mt-3">
                                        Lihat {{ $faqTotal }} pertanyaan lain
                                        <i class="bi bi-arrow-right"></i>
                                   </a>
                              @endif
                         </div>
                    </div>
                    <div class="col-lg-8">
                         <div class="accordion rs-accordion" id="{{ $faqId }}-accordion">
                              @foreach ($faqItems as $index => $faq)
                                   <div class="accordion-item" data-reveal data-reveal-delay="{{ $index * 60 }}">
                                        <h3 class="accordion-header"
                                             id="{{ $faqId }}-heading-{{ $faq->id }}">
                                             <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                                  type="button" data-bs-toggle="collapse"
                                                  data-bs-target="#{{ $faqId }}-collapse-{{ $faq->id }}"
                                                  aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                                  aria-controls="{{ $faqId }}-collapse-{{ $faq->id }}">
                                                  {{ $faq->question }}
                                             </button>
                                        </h3>
                                        <div id="{{ $faqId }}-collapse-{{ $faq->id }}"
                                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                             aria-labelledby="{{ $faqId }}-heading-{{ $faq->id }}"
                                             data-bs-parent="#{{ $faqId }}-accordion">
                                             <div class="accordion-body">
                                                  {!! nl2br(e($faq->answer)) !!}
                                             </div>
                                        </div>
                                   </div>
                              @endforeach
                         </div>
                    </div>
               </div>
          </div>
     </section>
@endif
