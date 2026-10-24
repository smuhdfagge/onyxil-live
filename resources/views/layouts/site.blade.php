@php
    /** @var \App\Support\Seo $seo */
    $seo ??= \App\Support\Seo::make($s['site_name'] ?? 'Onyx Innovations Ltd');
    $canonical = $seo->canonical ?? url()->current();
    $ogImage = $seo->image ?? asset('images/og-default.jpg');
    $siteName = $s['site_name'] ?? 'Onyx Innovations Ltd';
@endphp
<!DOCTYPE html>
<html lang="en-NG" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $seo->fullTitle() }}</title>
    <meta name="description" content="{{ $seo->description }}">
    @if (! empty($s['seo_keywords']))<meta name="keywords" content="{{ $s['seo_keywords'] }}">@endif
    <meta name="robots" content="{{ $seo->index ? 'index, follow, max-image-preview:large' : 'noindex, follow' }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="theme-color" content="#0f2a4a">
    <meta name="color-scheme" content="light">
    <meta name="format-detection" content="telephone=no">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="en_NG">
    <meta property="og:type" content="{{ $seo->type }}">
    <meta property="og:title" content="{{ $seo->title }}">
    <meta property="og:description" content="{{ $seo->description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="{{ $seo->title }}">
    @if ($seo->published)<meta property="article:published_time" content="{{ $seo->published }}">@endif
    @if ($seo->modified)<meta property="article:modified_time" content="{{ $seo->modified }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo->title }}">
    <meta name="twitter:description" content="{{ $seo->description }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    @if (! empty($s['google_verification']))<meta name="google-site-verification" content="{{ $s['google_verification'] }}">@endif
    @if (! empty($s['bing_verification']))<meta name="msvalidate.01" content="{{ $s['bing_verification'] }}">@endif

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ url('site.webmanifest') }}">
    <link rel="alternate" type="application/rss+xml" title="{{ $siteName }} insights" href="{{ route('feed') }}">

    <link rel="preload" href="{{ asset('fonts/hanken-grotesk-latin-400-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/hanken-grotesk-latin-700-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    <script>document.documentElement.classList.replace('no-js','js')</script>

    @php $graph = $seo->jsonLd(); @endphp
    @if ($graph)
    <script type="application/ld+json">{!! \App\Support\Seo::ldJson(['@graph' => $graph]) !!}</script>
    @endif
    @stack('head')

    @if (! empty($s['google_analytics']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $s['google_analytics'] }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',@json($s['google_analytics']));</script>
    @endif
</head>
<body class="@yield('body_class')">
    <a class="skip" href="#main">Skip to content</a>
    <div class="progress" aria-hidden="true"><span></span></div>

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    @if (! empty($s['whatsapp']))
        <a class="wa-float" href="https://wa.me/{{ preg_replace('/\D/', '', $s['whatsapp']) }}" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
            @include('partials.icon', ['name' => 'whatsapp', 'size' => 26])
        </a>
    @endif

    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
