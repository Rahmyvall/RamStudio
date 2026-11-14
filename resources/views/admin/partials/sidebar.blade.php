@php
     $userName = auth()->user()->name ?? 'Administrator';
     $initials = collect(explode(' ', $userName))
         ->filter()
         ->take(2)
         ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
         ->implode('');
     $active = fn(string $pattern) => request()->routeIs($pattern) ? ' is-active' : '';

     $navigation = [
         'Utama' => [
             [
                 'route' => 'admin.dashboard',
                 'pattern' => 'admin.dashboard*',
                 'icon' => 'fa-dashboard',
                 'label' => 'Dashboard',
             ],
             [
                 'route' => 'admin.inquiries.index',
                 'pattern' => 'admin.inquiries*',
                 'icon' => 'fa-envelope-o',
                 'label' => 'Pesan Masuk',
             ],
         ],
         'Katalog' => [
             [
                 'route' => 'admin.service-categories.index',
                 'pattern' => 'admin.service-categories*',
                 'icon' => 'fa-tags',
                 'label' => 'Kategori Layanan',
             ],
             [
                 'route' => 'admin.services.index',
                 'pattern' => 'admin.services*',
                 'icon' => 'fa-cogs',
                 'label' => 'Layanan',
             ],
             [
                 'route' => 'admin.portfolio-categories.index',
                 'pattern' => 'admin.portfolio-categories*',
                 'icon' => 'fa-folder-open-o',
                 'label' => 'Kategori Portofolio',
             ],
             [
                 'route' => 'admin.portfolios.index',
                 'pattern' => 'admin.portfolios*',
                 'icon' => 'fa-briefcase',
                 'label' => 'Portofolio',
             ],
             [
                 'route' => 'admin.portfolio-images.index',
                 'pattern' => 'admin.portfolio-images*',
                 'icon' => 'fa-image',
                 'label' => 'Gambar Portofolio',
             ],
             [
                 'route' => 'admin.technologies.index',
                 'pattern' => 'admin.technologies*',
                 'icon' => 'fa-code',
                 'label' => 'Teknologi',
             ],
         ],
         'Penawaran' => [
             [
                 'route' => 'admin.pricing-packages.index',
                 'pattern' => 'admin.pricing-packages*',
                 'icon' => 'fa-credit-card',
                 'label' => 'Paket Harga',
             ],
             [
                 'route' => 'admin.pricing-features.index',
                 'pattern' => 'admin.pricing-features*',
                 'icon' => 'fa-list-ul',
                 'label' => 'Fitur Paket',
             ],
             [
                 'route' => 'admin.testimonials.index',
                 'pattern' => 'admin.testimonials*',
                 'icon' => 'fa-comments-o',
                 'label' => 'Testimoni',
             ],
             [
                 'route' => 'admin.faqs.index',
                 'pattern' => 'admin.faqs*',
                 'icon' => 'fa-question-circle-o',
                 'label' => 'FAQ',
             ],
         ],
         'Konten' => [
             [
                 'route' => 'admin.post-categories.index',
                 'pattern' => 'admin.post-categories*',
                 'icon' => 'fa-folder-o',
                 'label' => 'Kategori Artikel',
             ],
             [
                 'route' => 'admin.posts.index',
                 'pattern' => 'admin.posts*',
                 'icon' => 'fa-newspaper-o',
                 'label' => 'Artikel',
             ],
             ['route' => 'admin.tags.index', 'pattern' => 'admin.tags*', 'icon' => 'fa-hashtag', 'label' => 'Tag'],
             [
                 'route' => 'admin.pages.index',
                 'pattern' => 'admin.pages*',
                 'icon' => 'fa-file-text-o',
                 'label' => 'Halaman',
             ],
         ],
         'Tentang Kami' => [
             [
                 'route' => 'admin.about.profile.edit',
                 'pattern' => 'admin.about.profile*',
                 'icon' => 'fa-building-o',
                 'label' => 'Profil Perusahaan',
             ],
             [
                 'route' => 'admin.about.owners.index',
                 'pattern' => 'admin.about.owners*',
                 'icon' => 'fa-user-circle-o',
                 'label' => 'Owner',
             ],
             [
                 'route' => 'admin.about.teams.index',
                 'pattern' => 'admin.about.teams*',
                 'icon' => 'fa-users',
                 'label' => 'Anggota Tim',
             ],
             [
                 'route' => 'admin.about.values.index',
                 'pattern' => 'admin.about.values*',
                 'icon' => 'fa-balance-scale',
                 'label' => 'Visi, Misi & Nilai',
             ],
             [
                 'route' => 'admin.about.journeys.index',
                 'pattern' => 'admin.about.journeys*',
                 'icon' => 'fa-road',
                 'label' => 'Perjalanan',
             ],
             [
                 'route' => 'admin.about.statistics.index',
                 'pattern' => 'admin.about.statistics*',
                 'icon' => 'fa-bar-chart',
                 'label' => 'Statistik',
             ],
             [
                 'route' => 'admin.about.social-links.index',
                 'pattern' => 'admin.about.social-links*',
                 'icon' => 'fa-share-alt',
                 'label' => 'Media Sosial',
             ],
         ],
         'Sistem' => [
             [
                 'route' => 'admin.settings.index',
                 'pattern' => 'admin.settings*',
                 'icon' => 'fa-cog',
                 'label' => 'Pengaturan',
             ],
         ],
     ];
@endphp

<aside class="adm-sidebar">
     <a href="{{ route('admin.dashboard') }}" class="adm-brand">
          <span class="adm-brand__mark"><i class="fa fa-paw"></i></span>
          <span class="adm-brand__text">
               <strong>{{ config('app.name') }}</strong>
               <span>Admin Panel</span>
          </span>
     </a>

     <div class="adm-user">
          <span class="adm-user__avatar">{{ $initials ?: 'AD' }}</span>
          <span class="adm-user__meta">
               <span>Masuk sebagai</span>
               <strong>{{ $userName }}</strong>
          </span>
     </div>

     <nav class="adm-nav">
          @foreach ($navigation as $label => $items)
               <div class="adm-nav__label">{{ $label }}</div>
               @foreach ($items as $item)
                    <a href="{{ route($item['route']) }}" class="adm-nav__link{{ $active($item['pattern']) }}">
                         <i class="fa {{ $item['icon'] }}"></i>
                         <span>{{ $item['label'] }}</span>
                    </a>
               @endforeach
          @endforeach
     </nav>

     <div class="adm-sidebar__footer">
          <form method="POST" action="{{ route('admin.logout') }}">
               @csrf
               <button type="submit" class="adm-sidebar__logout">
                    <i class="fa fa-sign-out"></i> Keluar
               </button>
          </form>
     </div>
</aside>
