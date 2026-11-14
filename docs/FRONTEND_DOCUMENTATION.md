# RAMStudio — Frontend Website

Dokumentasi pengembangan **website resmi RAMStudio** (frontend publik) yang dibangun
dengan **Laravel + Blade + Bootstrap 5**, terintegrasi penuh dengan Backend Admin
Dashboard, REST API, dan database `ramstudio_db`.

---

## 1. Ringkasan

| Aspek | Keterangan |
| --- | --- |
| Framework | Laravel 13 |
| Frontend | Blade + Bootstrap 5.3.3 (lokal, tanpa CDN) |
| Ikon | Bootstrap Icons 1.11.3 (lokal) |
| Font | Plus Jakarta Sans (Google Fonts) |
| Konsep desain | Modern Premium — Navy + Electric Blue |
| Bahasa | Bahasa Indonesia |
| Sumber data | Eloquent model (database `ramstudio_db`) |
| Formulir | REST API `POST /api/v1/inquiries` |

### Identitas visual

| Token | Nilai | Penggunaan |
| --- | --- | --- |
| `--rs-navy-950` | `#050b1a` | Footer, hero background |
| `--rs-navy-900` | `#071127` | Warna utama brand |
| `--rs-blue-500` | `#1a6dff` | Aksen / CTA |
| `--rs-blue-600` | `#0b5cff` | Hover / gradient |
| `--rs-cyan-400` | `#22d3ee` | Aksen gradient |
| `--rs-ink` | `#0a1224` | Heading |
| `--rs-body` | `#4a5568` | Body text |

---

## 2. Struktur Folder

```
app/Http/Controllers/Frontend/
├── FrontendController.php     # Base: shared site data, SEO, FAQ
├── HomeController.php
├── AboutController.php
├── ServiceController.php
├── PortfolioController.php
├── PricingController.php
├── FaqController.php          # Pusat FAQ publik (/faq)
├── BlogController.php
├── ContactController.php
└── PageController.php

resources/views/frontend/
├── layouts/
│   └── app.blade.php
├── partials/
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   ├── seo.blade.php
│   ├── pagehead.blade.php
│   ├── cta.blade.php
│   └── faq.blade.php
├── home/index.blade.php
├── about/index.blade.php
├── services/{index,show}.blade.php
├── portfolios/{index,show}.blade.php
├── pricing/index.blade.php
├── faqs/index.blade.php
├── blog/{index,show}.blade.php
├── contact/index.blade.php
├── pages/show.blade.php
└── errors/404.blade.php

resources/views/errors/404.blade.php   # Error handler global (standalone)

public/frontend-dev/
├── css/ramstudio.css          # Design system (tokens, komponen, utilitas)
├── js/ramstudio.js            # Navbar, reveal, counter, filter, FAQ search, form AJAX
├── images/favicon.svg
└── vendors/
    ├── bootstrap/             # bootstrap.min.css + bundle.min.js
    └── bootstrap-icons/       # CSS + font woff/woff2
```

> Template asli `public/frontend-dev` (Astro/Tailwind "ScrewFast") **tidak dihapus**.
> Aset baru ditempatkan di subfolder `css/`, `js/`, `images/`, dan `vendors/` sehingga
> tidak ada konflik dengan file template asli.

---

## 3. Routing Frontend

| Method | URL | Route name | Controller |
| --- | --- | --- | --- |
| GET | `/` | `home` | `HomeController@index` |
| GET | `/tentang-kami` | `about` | `AboutController@index` |
| GET | `/layanan` | `services.index` | `ServiceController@index` |
| GET | `/layanan/{slug}` | `services.show` | `ServiceController@show` |
| GET | `/portofolio` | `portfolios.index` | `PortfolioController@index` |
| GET | `/portofolio/{slug}` | `portfolios.show` | `PortfolioController@show` |
| GET | `/harga` | `pricing` | `PricingController@index` |
| GET | `/faq` | `faqs.index` | `FaqController@index` |
| GET | `/blog` | `blog.index` | `BlogController@index` |
| GET | `/blog/{slug}` | `blog.show` | `BlogController@show` |
| GET | `/kontak` | `contact` | `ContactController@index` |
| GET | `/halaman/{slug}` | `pages.show` | `PageController@show` |

URL menggunakan slug SEO-friendly (Bahasa Indonesia), bukan ID numerik.

---

## 4. Integrasi Data

Semua halaman membaca data **langsung dari database** melalui Eloquent, sehingga
perubahan di Admin Dashboard langsung tampil di frontend (tanpa cache konten).

| Halaman | Sumber data | Filter |
| --- | --- | --- |
| Navbar/Footer | `settings`, `about_profiles`, `about_social_links`, `services` | `is_active` |
| Home | `services`, `portfolios`, `testimonials`, `pricing_packages`, `about_statistics`, `about_journeys`, `posts`, `faqs` | aktif/published |
| About | `about_profiles`, `about_owners`, `about_teams`, `about_values`, `about_journeys`, `about_statistics` | `is_active` |
| Services | `services`, `service_categories` | `is_active` |
| Portfolio | `portfolios`, `portfolio_categories`, `technologies`, `portfolio_images` | `is_published` |
| Pricing | `pricing_packages`, `pricing_features` | `is_active` |
| Blog | `posts`, `post_categories`, `tags` | `status = published` |
| Contact | `services`, `faqs`, `settings`, `about_social_links` | aktif |

### Tabel `abouts`

Permintaan menyebut tabel `abouts`; pada skema aktual backend, data profil brand dan
founder tersimpan pada tabel terpisah yang sudah ada:

- `about_profiles` — identitas brand (nama, tagline, deskripsi, visi, misi, logo,
  hero image, alamat, email, telepon, tahun berdiri).
- `about_owners` — profil founder/pemilik (nama, jabatan, foto, bio, email, LinkedIn,
  Instagram).
- `about_teams`, `about_values`, `about_journeys`, `about_statistics`,
  `about_social_links` — data pendukung halaman About.

Frontend **tidak membuat tabel baru** dan **tidak mengubah migration**; seluruh field
dipetakan sesuai migration aktual.

### Formulir konsultasi

Formulir di `/kontak` mengirim `POST /api/v1/inquiries` (endpoint publik yang sudah
ada) via `fetch()` dengan header `Accept: application/json` dan `X-CSRF-TOKEN`.
Respons sukses `201` menampilkan notifikasi hijau; error validasi `422` menampilkan
pesan per-field; kegagalan koneksi menampilkan pesan yang ramah.

### WhatsApp dengan pesan otomatis

Seluruh tombol **"Konsultasi Gratis"**, **"Hubungi Kami"**, **"Pilih Paket"**,
**"Konsultasi Sekarang"**, ikon WhatsApp melayang, dan tautan WhatsApp di footer
langsung membuka WhatsApp (`wa.me`) dengan pesan yang sudah terisi otomatis.

Helper terpusat: `app/Support/WhatsApp.php`

| Method | Fungsi |
| --- | --- |
| `WhatsApp::number($url, $phone)` | Normalisasi nomor/URL → digit internasional (`08xx` → `62xx`) |
| `WhatsApp::link($number, $message)` | Bangun URL `wa.me` + `?text=` (default: pesan konsultasi umum) |
| `WhatsApp::serviceLink($number, $name)` | Pesan khusus untuk satu layanan |
| `WhatsApp::packageLink($number, $name)` | Pesan khusus untuk satu paket harga |

Nomor WhatsApp diambil dari `about_social_links` (platform `WhatsApp`), dengan
fallback ke `settings.contact_phone`. Jika nomor tidak tersedia, tombol otomatis
kembali mengarah ke halaman `/kontak` (graceful fallback).

Variabel Blade yang tersedia di semua view frontend:

- `$whatsappNumber` — nomor digit internasional (mis. `6285664206273`)
- `$whatsappUrl` — URL `wa.me` lengkap dengan pesan default

**Pesan default:**

```
Halo RAMStudio! 👋

Saya tertarik untuk menggunakan jasa layanan digital dari RAMStudio.

Saya ingin berkonsultasi lebih lanjut mengenai kebutuhan proyek saya, khususnya
terkait pembuatan website, aplikasi, atau sistem informasi.

Mohon informasikan mengenai layanan, estimasi biaya, serta proses pengerjaannya.

Terima kasih. Saya menantikan informasi dan kesempatan untuk bekerja sama dengan
RAMStudio. 🙏
```

Untuk mengubah teks pesan, edit konstanta `WhatsApp::DEFAULT_MESSAGE`.

---

## 5. Komponen Desain

| Kelas | Fungsi |
| --- | --- |
| `.rs-container` | Wrapper max-width 1200px |
| `.rs-section` | Padding vertikal responsif |
| `.rs-section--surface` / `--navy` | Varian latar section |
| `.rs-heading` | Blok judul + deskripsi section |
| `.rs-eyebrow` | Label kecil di atas judul |
| `.rs-gradient-text` | Teks gradient biru–cyan |
| `.btn-rs-primary` / `-dark` / `-outline` / `-light` / `-ghost-light` | Tombol brand |
| `.rs-card` | Kartu layanan/nilai |
| `.rs-media-card` | Kartu portofolio/artikel dengan gambar |
| `.rs-price-card` (+ `--popular`) | Kartu paket harga |
| `.rs-quote` | Kartu testimoni |
| `.rs-step` | Kartu tahap proses |
| `.rs-stat` | Kartu statistik |
| `.rs-accordion` | Accordion FAQ (Bootstrap collapse) |
| `.rs-faqc-bar` / `.rs-faqc-help` / `.rs-faqc-toolbar` / `.rs-faq-list` | Pusat FAQ lengkap (`/faq`): pencarian instan, buka/tutup semua |
| `.rs-cta` | Band call-to-action |
| `.rs-info-card` | Kartu info kontak |
| `.rs-prose` | Typography konten artikel |
| `.rs-empty` | Empty state |
| `.rs-filters` / `.rs-filter` | Filter kategori |
| `.rs-pagination` | Pagination brand |

### JavaScript (`ramstudio.js`)

- Navbar shadow saat scroll + drawer mobile (tutup otomatis saat resize/link diklik).
- Scroll reveal via `IntersectionObserver` (`data-reveal`, `data-reveal-delay`).
- Animated counter (`data-count`) dengan easing.
- Back-to-top button.
- Client-side filter (`data-rs-filter-group`).
- Pusat FAQ: pencarian instan (`data-rs-faq-search`), buka/tutup semua
  (`data-rs-faq-toggle-all`), counter hasil (`data-rs-faq-count`) dan
  empty state (`data-rs-faq-empty`). Mendukung deep link `?q=`.
- AJAX submit formulir konsultasi + validasi klien.
- Lazy image fade-in.
- Menghormati `prefers-reduced-motion`.

---

## 6. SEO & Performa

- HTML5 semantik (`header`, `nav`, `main`, `section`, `article`, `footer`).
- Meta title & description unik per halaman.
- Canonical URL, `robots`, Open Graph, dan Twitter Card.
- JSON-LD `Organization` structured data.
- JSON-LD tambahan per halaman melalui `seo.schema_extra` — halaman `/faq`
  mengirim blok `FAQPage` agar pertanyaan berpeluang muncul sebagai rich result.
- Heading terstruktur (satu `h1` per halaman).
- `loading="lazy"` untuk gambar non-hero; `fetchpriority="high"` untuk hero.
- Aset CSS/JS di-host lokal (tanpa CDN) dan dimuat dengan `defer`.
- Halaman 404 kustom (branded) untuk route maupun error handler global.
- Mobile-first responsive (desktop, laptop, tablet, smartphone).
- Skip-link untuk aksesibilitas keyboard.

---

## 7. Menjalankan Frontend

```bash
# 1. Pastikan database & data siap
php artisan migrate
php artisan db:seed

# 2. Jalankan server
php artisan serve
```

Buka `http://127.0.0.1:8000`.

> Jika `php` tidak ada di PATH (Laragon), gunakan path lengkap, mis.
> `C:\laragon\bin\php\php-8.3.31-nts-Win32-vs16-x64\php.exe artisan serve`.

Tidak diperlukan build step (Vite/npm) — CSS dan JS frontend sudah statis di
`public/frontend-dev/`.

---

## 8. Hasil Pengujian

| Uji | Hasil |
| --- | --- |
| Semua halaman dapat diakses | ✅ 200 OK |
| Navigasi navbar & footer | ✅ |
| Data layanan dari DB | ✅ |
| Data portofolio + teknologi | ✅ |
| Data paket harga + fitur | ✅ |
| Data blog + kategori + tag | ✅ |
| Data About (profil, founder, visi/misi, nilai, journey, statistik, tim) | ✅ |
| Formulir konsultasi → `POST /api/v1/inquiries` | ✅ 201 Created |
| Tombol WhatsApp membuka chat dengan pesan otomatis | ✅ pesan terisi otomatis |
| Tidak ada tautan `wa.me` tanpa pesan (bare link) | ✅ 0 ditemukan |
| Filter kategori & pencarian | ✅ |
| Kondisi data kosong (empty state) | ✅ |
| Halaman 404 | ✅ 404 + halaman branded |
| Aset CSS/JS/font/ikon | ✅ 200 OK |
| Tidak ada error Laravel | ✅ log bersih |

---

## 9. Catatan Pemeliharaan

- Konten dikelola sepenuhnya dari **Admin Dashboard** (`/admin`).
- Untuk mengganti warna brand, ubah token di bagian `:root` pada
  `public/frontend-dev/css/ramstudio.css`.
- Template asli Astro di `public/frontend-dev/src` tidak dipakai saat runtime dan
  dapat dihapus jika tidak diperlukan lagi (setelah memastikan tidak ada dependensi).
- Jangan mengubah migration tanpa alasan teknis; frontend mengikuti skema yang ada.
