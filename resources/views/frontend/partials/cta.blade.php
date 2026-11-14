@php
     $ctaTitle = $ctaTitle ?? 'Siap Mewujudkan Ide Digital Anda?';
     $ctaText = $ctaText ?? 'Diskusikan kebutuhan Anda dengan tim kami. Konsultasi awal gratis, tanpa komitmen.';
     $ctaPrimary = $ctaPrimary ?? 'Mulai Konsultasi';
     $ctaPrimaryUrl = $ctaPrimaryUrl ?? ($whatsappUrl ?? route('contact'));
     $ctaPrimaryExternal = $ctaPrimaryExternal ?? !empty($whatsappUrl) && $ctaPrimaryUrl === $whatsappUrl;
     $ctaSecondary = $ctaSecondary ?? 'Lihat Layanan';
     $ctaSecondaryUrl = $ctaSecondaryUrl ?? route('services.index');
@endphp

<section class="rs-section rs-section--tight">
     <div class="rs-container">
          <div class="rs-cta" data-reveal>
               <span class="rs-eyebrow"><i class="bi bi-lightning-charge"></i>Mari Berkolaborasi</span>
               <h2>{{ $ctaTitle }}</h2>
               <p>{{ $ctaText }}</p>
               <div class="rs-cta__actions">
                    <a href="{{ $ctaPrimaryUrl }}"
                         @if ($ctaPrimaryExternal) target="_blank" rel="noopener noreferrer" @endif
                         class="btn btn-rs-primary btn-lg">
                         <i class="bi {{ $ctaPrimaryExternal ? 'bi-whatsapp' : 'bi-chat-dots' }}"></i>
                         {{ $ctaPrimary }}
                    </a>
                    <a href="{{ $ctaSecondaryUrl }}" class="btn btn-rs-ghost-light btn-lg">
                         {{ $ctaSecondary }}
                         <i class="bi bi-arrow-right"></i>
                    </a>
               </div>
          </div>
     </div>
</section>
