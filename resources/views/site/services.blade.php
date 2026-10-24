@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => 'IT services, done properly and looked after', 'lead' => 'From a company website to a system used across many locations, we design it, build it, host it and keep it running.', 'crumbs' => ['Services' => route('services.index')]])

<section class="section">
    <div class="wrap">
        <div class="tile-grid">
            @foreach ($services as $sv)
                <a href="{{ route('services.show', $sv) }}" class="tile" data-reveal>
                    <span class="tile-icon">@include('partials.icon', ['name' => $sv->icon, 'size' => 26, 'stroke' => 1.4])</span>
                    <h2>{{ $sv->title }}</h2>
                    <p>{{ $sv->summary }}</p>
                    <span class="link-arrow">Learn more @include('partials.icon', ['name' => 'arrow', 'size' => 16])</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

@if ($faqs->isNotEmpty())
<section class="section" style="padding-top:0">
    <div class="wrap faq-grid">
        <div class="section-head"><h2 class="h-display">Before you ask</h2></div>
        @include('partials.faq', ['faqs' => $faqs])
    </div>
</section>
@endif

@include('partials.cta')
@endsection
