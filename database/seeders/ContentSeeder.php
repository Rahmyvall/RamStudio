<?php

namespace Database\Seeders;

use App\Models\AboutJourney;
use App\Models\AboutOwner;
use App\Models\AboutProfile;
use App\Models\AboutSocialLink;
use App\Models\AboutStatistic;
use App\Models\AboutTeam;
use App\Models\AboutValue;
use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\PortfolioImage;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PricingFeature;
use App\Models\PricingPackage;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\Technology;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
  /**
   * Populate the dashboard with representative demo data so the admin
   * panels and API endpoints are not empty on a fresh install.
   */
  public function run(): void
  {
    $admin = User::query()->first();

    // Settings
    $settings = [
      ['key' => 'site_name', 'value' => 'RAMStudio', 'group' => 'general', 'type' => 'text'],
      ['key' => 'site_tagline', 'value' => 'Creative Digital Studio', 'group' => 'general', 'type' => 'text'],
      ['key' => 'contact_email', 'value' => 'hello@ramstudio.test', 'group' => 'contact', 'type' => 'text'],
      ['key' => 'contact_phone', 'value' => '+62 812 0000 0000', 'group' => 'contact', 'type' => 'text'],
      ['key' => 'company_address', 'value' => 'Jl. Contoh No. 1, Jakarta', 'group' => 'contact', 'type' => 'textarea'],
      ['key' => 'map_query', 'value' => '', 'group' => 'contact', 'type' => 'text'],
      ['key' => 'map_embed_url', 'value' => '', 'group' => 'contact', 'type' => 'textarea'],
    ];

    foreach ($settings as $setting) {
      Setting::query()->updateOrCreate(['key' => $setting['key']], $setting);
    }

    // Service categories + services
    $web = ServiceCategory::query()->updateOrCreate(
      ['slug' => 'web-development'],
      ['name' => 'Web Development', 'description' => 'Layanan pengembangan website.', 'sort_order' => 1, 'is_active' => true],
    );
    $design = ServiceCategory::query()->updateOrCreate(
      ['slug' => 'design'],
      ['name' => 'Design', 'description' => 'Layanan desain visual.', 'sort_order' => 2, 'is_active' => true],
    );

    $services = [
      ['service_category_id' => $web->id, 'name' => 'Company Profile Website', 'slug' => 'company-profile-website', 'short_description' => 'Website profil perusahaan yang profesional.', 'starting_price' => 5000000, 'is_featured' => true],
      ['service_category_id' => $web->id, 'name' => 'E-Commerce Website', 'slug' => 'e-commerce-website', 'short_description' => 'Toko online lengkap dengan pembayaran.', 'starting_price' => 12000000, 'is_featured' => true],
      ['service_category_id' => $design->id, 'name' => 'Brand Identity Design', 'slug' => 'brand-identity-design', 'short_description' => 'Logo dan identitas visual brand.', 'starting_price' => 3500000, 'is_featured' => false],
    ];

    foreach ($services as $service) {
      Service::query()->updateOrCreate(['slug' => $service['slug']], $service + ['is_active' => true]);
    }

    // Technologies
    $techNames = ['Laravel', 'Vue.js', 'Bootstrap', 'MySQL', 'Figma'];
    foreach ($techNames as $name) {
      Technology::query()->updateOrCreate(
        ['slug' => \Illuminate\Support\Str::slug($name)],
        ['name' => $name],
      );
    }

    // Portfolio categories + portfolios
    $portfolioCategory = PortfolioCategory::query()->updateOrCreate(
      ['slug' => 'corporate'],
      ['name' => 'Corporate'],
    );

    $portfolios = [
      ['title' => 'Sistem Informasi Perusahaan', 'slug' => 'sistem-informasi-perusahaan', 'client_name' => 'PT Maju Jaya', 'is_featured' => true],
      ['title' => 'Aplikasi Kasir Restoran', 'slug' => 'aplikasi-kasir-restoran', 'client_name' => 'Restoran Nusantara', 'is_featured' => false],
    ];

    foreach ($portfolios as $portfolio) {
      Portfolio::query()->updateOrCreate(
        ['slug' => $portfolio['slug']],
        $portfolio + [
          'portfolio_category_id' => $portfolioCategory->id,
          'description' => 'Proyek pengembangan digital bersama RAMStudio.',
          'project_date' => now()->subMonths(2)->toDateString(),
          'is_published' => true,
        ],
      );
    }

    // Pricing packages + features
    $basic = PricingPackage::query()->updateOrCreate(
      ['slug' => 'basic'],
      ['name' => 'Basic', 'description' => 'Cocok untuk UMKM.', 'price' => 2500000, 'price_label' => '/ proyek', 'is_popular' => false, 'is_active' => true],
    );
    $pro = PricingPackage::query()->updateOrCreate(
      ['slug' => 'professional'],
      ['name' => 'Professional', 'description' => 'Untuk bisnis yang berkembang.', 'price' => 7500000, 'price_label' => '/ proyek', 'is_popular' => true, 'is_active' => true],
    );

    foreach (['Free hosting 1 tahun', 'Desain responsif', 'SEO dasar'] as $feature) {
      PricingFeature::query()->updateOrCreate(
        ['pricing_package_id' => $basic->id, 'feature' => $feature],
        ['is_included' => true],
      );
    }
    foreach (['Semua fitur Basic', 'Integrasi pembayaran', 'Maintenance 6 bulan'] as $feature) {
      PricingFeature::query()->updateOrCreate(
        ['pricing_package_id' => $pro->id, 'feature' => $feature],
        ['is_included' => true],
      );
    }

    // Testimonials
    Testimonial::query()->updateOrCreate(
      ['client_name' => 'Budi Santoso'],
      [
        'client_position' => 'CEO',
        'company_name' => 'PT Maju Jaya',
        'message' => 'RAMStudio sangat profesional dan hasil kerjanya memuaskan.',
        'rating' => 5,
        'is_published' => true,
      ],
    );

    // FAQs
    Faq::query()->updateOrCreate(
      ['question' => 'Berapa lama proses pembuatan website?'],
      ['answer' => 'Biasanya 2-4 minggu tergantung kompleksitas proyek.', 'sort_order' => 1, 'is_active' => true],
    );

    // Pages
    Page::query()->updateOrCreate(
      ['slug' => 'tentang-kami'],
      [
        'title' => 'Tentang Kami',
        'content' => '<p>RAMStudio adalah creative digital studio.</p>',
        'meta_title' => 'Tentang RAMStudio',
        'meta_description' => 'Kenali RAMStudio lebih dekat.',
        'is_published' => true,
      ],
    );

    // Post categories, tags, posts
    $postCategory = PostCategory::query()->updateOrCreate(
      ['slug' => 'tips-teknologi'],
      ['name' => 'Tips Teknologi'],
    );

    $tag = Tag::query()->updateOrCreate(
      ['slug' => 'laravel'],
      ['name' => 'Laravel'],
    );

    $post = Post::query()->updateOrCreate(
      ['slug' => 'memulai-website-bisnis-anda'],
      [
        'post_category_id' => $postCategory->id,
        'user_id' => $admin?->id,
        'title' => 'Memulai Website Bisnis Anda',
        'excerpt' => 'Panduan singkat membangun website bisnis yang efektif.',
        'content' => '<p>Website adalah aset penting bagi bisnis modern.</p>',
        'status' => 'published',
        'published_at' => now(),
      ],
    );
    $post->tags()->syncWithoutDetaching([$tag->id]);

    // Inquiries
    $serviceIds = Service::query()->pluck('id')->take(2)->all();

    $inquiry = Inquiry::query()->firstOrCreate(
      ['email' => 'calon.klien@example.com'],
      [
        'name' => 'Calon Klien',
        'phone' => '08123456789',
        'company' => 'PT Contoh',
        'subject' => 'Konsultasi Website',
        'message' => 'Saya ingin membuat website company profile.',
        'budget' => '5-10 juta',
        'status' => 'new',
      ],
    );
    $inquiry->services()->syncWithoutDetaching($serviceIds);

    // About / company profile
    AboutProfile::query()->updateOrCreate(
      ['id' => 1],
      [
        'company_name' => 'RAMStudio',
        'tagline' => 'Creative Digital Studio',
        'description' => 'RAMStudio adalah studio digital yang membantu bisnis tumbuh melalui website, aplikasi, dan desain yang berkesan.',
        'vision' => 'Menjadi studio digital terpercaya di Indonesia.',
        'mission' => 'Menghadirkan solusi digital berkualitas dengan pendekatan yang personal.',
        'address' => 'Jl. Contoh No. 1, Jakarta',
        'email' => 'hello@ramstudio.test',
        'phone' => '+62 812 0000 0000',
        'founded_year' => 2018,
      ],
    );

    AboutOwner::query()->updateOrCreate(
      ['name' => 'Rama Aditya'],
      [
        'position' => 'Founder & CEO',
        'bio' => 'Memimpin RAMStudio sejak 2018 dengan fokus pada kualitas dan kepuasan klien.',
        'email' => 'rama@ramstudio.test',
        'sort_order' => 1,
        'is_active' => true,
      ],
    );

    $team = [
      ['name' => 'Sinta Dewi', 'position' => 'Lead Designer', 'sort_order' => 1],
      ['name' => 'Budi Prakoso', 'position' => 'Full-stack Developer', 'sort_order' => 2],
      ['name' => 'Nadia Putri', 'position' => 'Project Manager', 'sort_order' => 3],
    ];
    foreach ($team as $member) {
      AboutTeam::query()->updateOrCreate(
        ['name' => $member['name']],
        $member + ['is_active' => true],
      );
    }

    $values = [
      ['title' => 'Visi', 'type' => 'vision', 'description' => 'Menjadi studio digital terpercaya di Indonesia.', 'icon' => 'fa fa-eye', 'sort_order' => 1],
      ['title' => 'Misi', 'type' => 'mission', 'description' => 'Menghadirkan solusi digital berkualitas dengan pendekatan personal.', 'icon' => 'fa fa-bullseye', 'sort_order' => 2],
      ['title' => 'Integritas', 'type' => 'value', 'description' => 'Jujur dan transparan dalam setiap pekerjaan.', 'icon' => 'fa fa-handshake-o', 'sort_order' => 3],
      ['title' => 'Inovasi', 'type' => 'value', 'description' => 'Selalu mencari cara baru yang lebih baik.', 'icon' => 'fa fa-lightbulb-o', 'sort_order' => 4],
    ];
    foreach ($values as $value) {
      AboutValue::query()->updateOrCreate(
        ['title' => $value['title'], 'type' => $value['type']],
        $value + ['is_active' => true],
      );
    }

    $journeys = [
      ['year' => 2018, 'title' => 'RAMStudio Didirikan', 'description' => 'Berdiri sebagai studio freelance kecil.', 'sort_order' => 1],
      ['year' => 2020, 'title' => 'Tim Berkembang', 'description' => 'Menambah anggota tim desain dan developer.', 'sort_order' => 2],
      ['year' => 2023, 'title' => '100+ Proyek', 'description' => 'Menyelesaikan lebih dari 100 proyek digital.', 'sort_order' => 3],
    ];
    foreach ($journeys as $journey) {
      AboutJourney::query()->updateOrCreate(
        ['year' => $journey['year'], 'title' => $journey['title']],
        $journey + ['is_active' => true],
      );
    }

    $statistics = [
      ['label' => 'Proyek Selesai', 'value' => '120', 'suffix' => '+', 'icon' => 'fa fa-briefcase', 'sort_order' => 1],
      ['label' => 'Klien Puas', 'value' => '80', 'suffix' => '+', 'icon' => 'fa fa-smile-o', 'sort_order' => 2],
      ['label' => 'Tahun Pengalaman', 'value' => '6', 'suffix' => '', 'icon' => 'fa fa-calendar', 'sort_order' => 3],
      ['label' => 'Anggota Tim', 'value' => '12', 'suffix' => '', 'icon' => 'fa fa-users', 'sort_order' => 4],
    ];
    foreach ($statistics as $statistic) {
      AboutStatistic::query()->updateOrCreate(
        ['label' => $statistic['label']],
        $statistic + ['is_active' => true],
      );
    }

    $socialLinks = [
      ['platform' => 'Instagram', 'label' => '@ramstudio', 'url' => 'https://instagram.com/ramstudio', 'icon' => 'fa fa-instagram', 'sort_order' => 1],
      ['platform' => 'LinkedIn', 'label' => 'RAMStudio', 'url' => 'https://linkedin.com/company/ramstudio', 'icon' => 'fa fa-linkedin', 'sort_order' => 2],
      ['platform' => 'WhatsApp', 'label' => '+62 812 0000 0000', 'url' => 'https://wa.me/6281200000000', 'icon' => 'fa fa-whatsapp', 'sort_order' => 3],
    ];
    foreach ($socialLinks as $link) {
      AboutSocialLink::query()->updateOrCreate(
        ['platform' => $link['platform']],
        $link + ['is_active' => true],
      );
    }
  }
}
