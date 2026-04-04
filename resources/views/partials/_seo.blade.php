{{--
    SEO Meta Tags Partial
    Usage: Push data from child views via @section or variables:
      - $seoTitle        (string)  Page title
      - $seoDescription  (string)  Meta description (max 160 chars)
      - $seoKeywords     (string)  Comma-separated keywords
      - $seoImage        (string)  Full URL to OG image
      - $seoUrl          (string)  Canonical URL (defaults to current)
      - $seoType         (string)  OG type: website|article|product (default: website)
      - $seoSchema       (array)   JSON-LD structured data array
      - $seoNoindex      (bool)    Set true to noindex this page
--}}

@php
    $siteName   = \App\Models\SystemSetting::get('site_name_ar', config('app.name', 'Endak'));
    $siteNameEn = \App\Models\SystemSetting::get('site_name_en', 'Endak');
    $siteLogo   = asset(\App\Models\SystemSetting::get('site_logo', 'home.png'));
    $locale     = app()->getLocale();
    $altLocale  = $locale === 'ar' ? 'en' : 'ar';

    $_title       = isset($seoTitle) ? $seoTitle : trim($__env->yieldContent('title', $siteName));
    $_fullTitle   = Str::contains($_title, $siteNameEn) || Str::contains($_title, $siteName)
                    ? $_title
                    : $_title . ' | ' . ($locale === 'ar' ? $siteName : $siteNameEn);
    $_description = isset($seoDescription) ? $seoDescription : \App\Models\SystemSetting::get('site_description_' . $locale, __('messages.seo_default_description'));
    $_keywords    = isset($seoKeywords) ? $seoKeywords : \App\Models\SystemSetting::get('site_keywords_' . $locale, __('messages.seo_default_keywords'));
    $_image       = isset($seoImage) ? $seoImage : $siteLogo;
    $_url         = isset($seoUrl) ? $seoUrl : url()->current();
    $_type        = isset($seoType) ? $seoType : 'website';
    $_noindex     = isset($seoNoindex) && $seoNoindex;

    // Truncate description to 160 chars
    $_description = Str::limit(strip_tags($_description), 160, '...');
@endphp

{{-- Basic Meta --}}
<meta name="description" content="{{ $_description }}">
<meta name="keywords" content="{{ $_keywords }}">
<meta name="author" content="{{ $siteNameEn }}">
@if($_noindex)
<meta name="robots" content="noindex, nofollow">
@else
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
@endif

{{-- Canonical --}}
<link rel="canonical" href="{{ $_url }}">

{{-- Alternate Languages --}}
<link rel="alternate" hreflang="{{ $locale }}" href="{{ $_url }}">
<link rel="alternate" hreflang="{{ $altLocale }}" href="{{ $_url }}">
<link rel="alternate" hreflang="x-default" href="{{ $_url }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $_type }}">
<meta property="og:site_name" content="{{ $locale === 'ar' ? $siteName : $siteNameEn }}">
<meta property="og:title" content="{{ $_fullTitle }}">
<meta property="og:description" content="{{ $_description }}">
<meta property="og:url" content="{{ $_url }}">
<meta property="og:image" content="{{ $_image }}">
<meta property="og:locale" content="{{ $locale === 'ar' ? 'ar_AR' : 'en_US' }}">
<meta property="og:locale:alternate" content="{{ $locale === 'ar' ? 'en_US' : 'ar_AR' }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $_fullTitle }}">
<meta name="twitter:description" content="{{ $_description }}">
<meta name="twitter:image" content="{{ $_image }}">

{{-- Favicon --}}
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

{{-- JSON-LD: Organization (always present) --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $siteNameEn,
    'alternateName' => $siteName,
    'url' => url('/'),
    'logo' => $siteLogo,
    'sameAs' => array_filter([
        \App\Models\SystemSetting::get('social_facebook'),
        \App\Models\SystemSetting::get('social_twitter'),
        \App\Models\SystemSetting::get('social_instagram'),
    ]),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- JSON-LD: WebSite with SearchAction --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $siteNameEn,
    'alternateName' => $siteName,
    'url' => url('/'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => route('services.search') . '?q={search_term_string}',
        ],
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- JSON-LD: BreadcrumbList (if $seoBreadcrumbs is set) --}}
@if(isset($seoBreadcrumbs) && count($seoBreadcrumbs) > 0)
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($seoBreadcrumbs)->map(function($crumb, $i) {
        return [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $crumb['name'],
            'item' => $crumb['url'] ?? null,
        ];
    })->values()->toArray(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

{{-- JSON-LD: Custom schema (if $seoSchema is set) --}}
@if(isset($seoSchema))
<script type="application/ld+json">
{!! json_encode($seoSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
