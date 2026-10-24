@php
    $nav = [
        'Overview' => [
            ['Dashboard', route('admin.dashboard'), 'admin.dashboard', 'chart', null],
            ['Messages', route('admin.messages'), 'admin.messages*', 'mail', $unreadCount],
            ['Project enquiries', route('admin.quotes'), 'admin.quotes*', 'spark', $newQuoteCount],
            ['Subscribers', route('admin.subscribers'), 'admin.subscribers*', 'users', null],
        ],
        'Content' => collect(\App\Admin\Resources::all())->except('seo')->map(fn ($r, $k) => [$r['label'], route('admin.resource.index', $k), $k, null, null])->values()->all(),
        'Site' => [
            ['Page SEO', route('admin.resource.index', 'seo'), 'seo', 'search', null],
            ['Settings', route('admin.settings'), 'admin.settings*', 'gear', null],
            ['Your account', route('admin.account'), 'admin.account*', 'shield', null],
        ],
    ];
    $current = request()->route('resource');
    $isActive = fn ($pattern) => request()->routeIs($pattern) || $current === $pattern;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') · Onyx admin</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body>
<div class="shell">
    <aside class="side" data-side>
        <a href="{{ route('admin.dashboard') }}" class="side-brand"><img src="{{ asset('images/logo-light.png') }}" alt="Onyx Innovations" width="110"></a>
        <nav>
            @foreach ($nav as $group => $items)
                <p class="side-group">{{ $group }}</p>
                <ul>
                    @foreach ($items as [$label, $href, $pattern, $icon, $badge])
                        <li><a href="{{ $href }}" @class(['on' => $isActive($pattern)])>
                            <span>{{ $label }}</span>
                            @if ($badge)<b class="badge">{{ $badge }}</b>@endif
                        </a></li>
                    @endforeach
                </ul>
            @endforeach
        </nav>
        <div class="side-foot">
            <a href="{{ route('home') }}" target="_blank" rel="noopener">View website ↗</a>
            <form method="post" action="{{ route('admin.logout') }}">@csrf<button type="submit">Sign out</button></form>
        </div>
    </aside>

    <div class="main">
        <header class="top">
            <button class="side-toggle" type="button" data-side-toggle aria-label="Show menu">☰</button>
            <h1>@yield('title', 'Admin')</h1>
            <div class="top-actions">@yield('actions')</div>
        </header>

        @if (session('status'))
            <div class="flash" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash flash-err" role="alert">Please fix the highlighted fields and save again.</div>
        @endif

        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
<script src="{{ asset('js/admin.js') }}?v={{ filemtime(public_path('js/admin.js')) }}" defer></script>
</body>
</html>
