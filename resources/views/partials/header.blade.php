@php
    $links = [
        ['Services', route('services.index'), 'services.*'],
        ['Products', route('products.index'), 'products.*'],
        ['Work', route('work.index'), 'work.*'],
        ['About', route('about'), 'about'],
        ['Insights', route('insights.index'), 'insights.*'],
        ['Contact', route('contact'), 'contact'],
    ];
@endphp
<header class="site-header" data-header>
    <div class="wrap header-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ $s['site_name'] ?? 'Onyx Innovations' }} home">
            <img src="{{ asset('images/logo-dark.png') }}" alt="Onyx Innovations Limited" width="124" height="55">
        </a>

        <nav class="nav" aria-label="Main">
            <ul>
                @foreach ($links as [$label, $href, $pattern])
                    <li class="{{ $label === 'Services' ? 'has-mega' : '' }}">
                        <a href="{{ $href }}" @class(['active' => request()->routeIs($pattern)]) @if(request()->routeIs($pattern)) aria-current="page" @endif>{{ $label }}</a>
                        @if ($label === 'Services' && $navServices->isNotEmpty())
                            <div class="mega" role="group" aria-label="Services">
                                <div class="mega-grid">
                                    @foreach ($navServices as $sv)
                                        <a href="{{ route('services.show', $sv) }}" class="mega-item">
                                            <span class="mega-icon">@include('partials.icon', ['name' => $sv->icon, 'size' => 20])</span>
                                            <span>{{ $sv->title }}</span>
                                        </a>
                                    @endforeach
                                </div>
                                @foreach ($navProducts->take(1) as $p)
                                    <a href="{{ route('products.show', $p) }}" class="mega-feature">
                                        <span class="mega-feature-label">Our product</span>
                                        <strong>{{ $p->title }}</strong>
                                        <span>{{ $p->tagline }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <a href="{{ route('quote') }}" class="btn btn-primary btn-sm header-cta">Start a project</a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" data-menu-toggle>
            <span class="sr-only">Open menu</span>
            <span class="burger" aria-hidden="true"><i></i><i></i></span>
        </button>
    </div>

    <div class="mobile-menu" id="mobile-menu" hidden data-menu>
        <nav aria-label="Mobile">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                @foreach ($links as [$label, $href])
                    <li><a href="{{ $href }}">{{ $label }}</a></li>
                @endforeach
                <li><a href="{{ route('careers.index') }}">Careers</a></li>
            </ul>
        </nav>
        <a href="{{ route('quote') }}" class="btn btn-primary">Start a project</a>
        <div class="mobile-contact">
            @if (! empty($s['email']))<a href="mailto:{{ $s['email'] }}">{{ $s['email'] }}</a>@endif
            @if (! empty($s['phone']))<a href="tel:{{ preg_replace('/\s+/', '', $s['phone']) }}">{{ $s['phone'] }}</a>@endif
        </div>
    </div>
</header>
