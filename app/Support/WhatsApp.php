<?php

namespace App\Support;

/**
 * Builds WhatsApp (wa.me) deep links with a pre-filled, professional
 * consultation message so prospective clients can start a conversation
 * with RAMStudio in a single tap.
 */
class WhatsApp
{
  /**
   * The default consultation message used by every "Hubungi Kami" /
   * "Konsultasi Gratis" call-to-action across the website.
   */
  public const DEFAULT_MESSAGE = "Halo RAMStudio! 👋\n\n"
    . "Saya tertarik untuk menggunakan jasa layanan digital dari RAMStudio.\n\n"
    . "Saya ingin berkonsultasi lebih lanjut mengenai kebutuhan proyek saya, khususnya terkait pembuatan website, aplikasi, atau sistem informasi.\n\n"
    . "Mohon informasikan mengenai layanan, estimasi biaya, serta proses pengerjaannya.\n\n"
    . "Terima kasih. Saya menantikan informasi dan kesempatan untuk bekerja sama dengan RAMStudio. 🙏";

  /**
   * Normalise a WhatsApp URL or a local phone number into the
   * international digits-only format required by wa.me.
   */
  public static function number(?string $url, ?string $phone = null): ?string
  {
    $candidate = $url ?: $phone;

    if (! $candidate) {
      return null;
    }

    $digits = preg_replace('/\D+/', '', $candidate);

    if (! $digits) {
      return null;
    }

    // Convert a local Indonesian number (08xx) to international form.
    if (str_starts_with($digits, '0')) {
      $digits = '62' . substr($digits, 1);
    }

    return $digits;
  }

  /**
   * Build a wa.me link with an optional custom message.
   *
   * Returns null when no valid number is available so callers can fall
   * back to the contact page.
   */
  public static function link(?string $number, ?string $message = null): ?string
  {
    if (! $number) {
      return null;
    }

    $url = 'https://wa.me/' . $number;
    $message = $message ?? self::DEFAULT_MESSAGE;

    if ($message !== '') {
      $url .= '?text=' . rawurlencode($message);
    }

    return $url;
  }

  /**
   * Build a consultation link for a specific service.
   */
  public static function serviceLink(?string $number, string $serviceName): ?string
  {
    $message = "Halo RAMStudio! 👋\n\n"
      . "Saya tertarik dengan layanan *{$serviceName}*.\n\n"
      . "Saya ingin berkonsultasi lebih lanjut mengenai kebutuhan proyek saya.\n\n"
      . "Mohon informasikan mengenai detail layanan, estimasi biaya, serta proses pengerjaannya.\n\n"
      . "Terima kasih. Saya menantikan informasi dan kesempatan untuk bekerja sama dengan RAMStudio. 🙏";

    return self::link($number, $message);
  }

  /**
   * Build a consultation link for a specific pricing package.
   */
  public static function packageLink(?string $number, string $packageName): ?string
  {
    $message = "Halo RAMStudio! 👋\n\n"
      . "Saya tertarik dengan paket *{$packageName}* yang ditawarkan RAMStudio.\n\n"
      . "Saya ingin berkonsultasi lebih lanjut mengenai kesesuaian paket tersebut dengan kebutuhan proyek saya.\n\n"
      . "Mohon informasikan mengenai detail paket, estimasi biaya, serta proses pengerjaannya.\n\n"
      . "Terima kasih. Saya menantikan informasi dan kesempatan untuk bekerja sama dengan RAMStudio. 🙏";

    return self::link($number, $message);
  }
}
