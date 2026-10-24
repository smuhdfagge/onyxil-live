@extends('layouts.site')
@section('body_class', 'page-product')
@section('content')
<section class="page-hero">
    <div class="page-hero-art" aria-hidden="true"><canvas data-lattice data-density="0.5"></canvas></div>
    <div class="wrap product-hero-grid">
        <div>
            <nav class="crumbs" aria-label="Breadcrumb"><ol><li><a href="{{ route('home') }}">Home</a></li><li><a href="{{ route('products.index') }}">Products</a></li><li aria-current="page">{{ $product->title }}</li></ol></nav>
            <p class="product-badge"><span class="dot"></span>{{ $product->status }}, built and run by Onyx Innovations</p>
            @if ($product->logo)<img src="{{ $product->mediaUrl('logo') }}" alt="{{ $product->title }} logo" class="product-logo">@endif
            <h1 class="page-title" data-split>{{ $product->title }}</h1>
            <p class="product-tagline" style="font-size:clamp(1.6rem,3vw,2.6rem);margin-top:14px">{{ $product->tagline }}</p>
            <p class="page-lead">{{ $product->summary }}</p>
            <div class="btn-row">
                @if ($product->url)<a href="{{ $product->url }}" class="btn btn-gold btn-lg" target="_blank" rel="noopener" data-magnetic><span>{{ $product->cta_label ?: 'Visit website' }}</span>@include('partials.icon', ['name' => 'external', 'size' => 16])</a>@endif
                <a href="{{ route('contact', ['subject' => $product->title.' demo']) }}" class="btn btn-line btn-lg"><span>Ask for a demo</span></a>
            </div>
        </div>
        @if ($product->image)
            <img src="{{ $product->mediaUrl() }}" alt="{{ $product->title }} screenshot" style="border-radius:14px">
        @else
            @include('partials.ledger', ['product' => $product])
        @endif
    </div>
</section>

@if ($features)
<section class="section">
    <div class="wrap">
        <div class="section-head"><h2 class="h-display">What it does</h2></div>
        <div class="feature-grid">
            @foreach ($features as [$ft, $fd])
                <div class="feature" data-reveal>
                    <h3>@include('partials.icon', ['name' => 'check', 'size' => 20]){{ $ft }}</h3>
                    @if ($fd)<p>{{ $fd }}</p>@endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($product->audiences)
<section class="section process">
    <div class="wrap">
        <div class="section-head"><h2 class="h-display">Made for the whole business</h2></div>
        <div class="audiences">
            @foreach (\App\Http\Controllers\SiteController::pairs($product->audiences) as [$a, $d])
                <div data-reveal><h3 style="font-family:var(--f-display);font-size:1.9rem;margin-bottom:8px">{{ $a }}</h3><p style="color:var(--ink-soft)">{{ $d }}</p></div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($body)
<section class="section">
    <div class="wrap"><div class="prose" data-reveal>{!! $body !!}</div></div>
</section>
@endif

@if ($product->pricing_note)
<section class="section" style="padding-top:0">
    <div class="wrap">
        <div class="price-note" data-reveal>
            <p>{{ $product->pricing_note }}</p>
            @if ($product->url)<a href="{{ $product->url }}" class="btn btn-ink btn-lg" target="_blank" rel="noopener"><span>See plans</span>@include('partials.icon', ['name' => 'external', 'size' => 16])</a>@endif
        </div>
    </div>
</section>
@endif

@if ($faqs->isNotEmpty())
<section class="section" style="padding-top:0">
    <div class="wrap faq-grid">
        <div class="section-head"><h2 class="h-display">{{ $product->title }} questions</h2></div>
        @include('partials.faq', ['faqs' => $faqs])
    </div>
</section>
@endif

@include('partials.cta')
@endsection
