@php
     $seoTitle = $seo['title'] ?? ($site['name'] ?? 'RAMStudio');
     $seoDescription = $seo['description'] ?? ($site['description'] ?? '');
     $seoImage = $seo['image'] ?? ($site['hero_image'] ?? null);
     $seoType = $seo['type'] ?? 'website';
     $seoCanonical = $seo['canonical'] ?? url()->current();
     $seoRobots = $seo['robots'] ?? 'index, follow';
     $siteName = $site['name'] ?? 'RAMStudio';
     $seoDescription = \Illuminate\Support\Str::limit(strip_tags((string) $seoDescription), 158);

     $structuredData = array_filter(
         [
             '@context' => 'https://schema.org',
             '@type' => 'Organization',
             'name' => $siteName,
             'url' => url('/'),
             'description' => !empty($site['description'])
                 ? \Illuminate\Support\Str::limit(strip_tags((string) $site['description']), 300)
                 : null,
             'logo' => !empty($site['logo']) ? url($site['logo']) : null,
             'email' => $site['email'] ?? null,
             'telephone' => $site['phone'] ?? null,
             'address' => !empty($site['address'])
                 ? ['@type' => 'PostalAddress', 'streetAddress' => $site['address']]
                 : null,
             'sameAs' => $socialLinks->pluck('url')->filter()->values()->all() ?: null,
         ],
         fn($value) => $value !== null && $value !== [],
     );

     // Additional JSON-LD blocks supplied per page (e.g. FAQPage).
     $schemaExtra = array_filter((array) ($seo['schema_extra'] ?? []));
@endphp

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<meta name="robots" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $seoCanonical }}">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonical }}">
@if ($seoImage)
     <meta property="og:image" content="{{ url($seoImage) }}">
@endif

{{-- Twitter --}}
<meta name="twitter:card" content="{{ $seoImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if ($seoImage)
     <meta name="twitter:image" content="{{ url($seoImage) }}">
@endif

{{-- Favicon --}}
<link rel="icon" type="image/svg+xml" href="{{ asset('frontend-dev/images/favicon.svg') }}">

{{-- Structured data --}}
<script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@foreach ($schemaExtra as $schemaBlock)
     <script type="application/ld+json">{!! json_encode($schemaBlock, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endforeach
