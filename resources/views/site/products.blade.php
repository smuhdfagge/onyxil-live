@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => 'Software we build and run ourselves', 'lead' => 'Alongside client work, we build our own products for problems we keep seeing in Nigerian organisations.', 'crumbs' => ['Products' => route('products.index')]])

@forelse ($products as $p)
<section class="section product-spot" @if(!$loop->first) style="border-top:0" @endif>
    <div class="wrap product-grid">
        <div class="product-copy" data-reveal>
            <p class="product-badge"><span class="dot"></span>{{ $p->status }}</p>
            @if ($p->logo)<img src="{{ $p->mediaUrl('logo') }}" alt="{{ $p->title }} logo" class="product-logo">@endif
            <h2 class="h-display">{{ $p->title }}</h2>
            <p class="product-tagline">{{ $p->tagline }}</p>
            <p>{{ $p->summary }}</p>
            <div class="btn-row">
                <a href="{{ route('products.show', $p) }}" class="btn btn-gold"><span>Explore {{ $p->title }}</span></a>
                @if ($p->url)<a href="{{ $p->url }}" class="btn btn-line" target="_blank" rel="noopener"><span>{{ $p->cta_label ?: 'Visit website' }}</span>@include('partials.icon', ['name' => 'external', 'size' => 16])</a>@endif
            </div>
        </div>
        @if ($p->image)
            <img src="{{ $p->mediaUrl() }}" alt="{{ $p->title }} screenshot" style="border-radius:14px" loading="lazy">
        @else
            @include('partials.ledger', ['product' => $p])
        @endif
    </div>
</section>
@empty
<section class="section"><div class="wrap"><p class="empty">Products will appear here soon.</p></div></section>
@endforelse

@include('partials.cta')
@endsection
