@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => $service->title, 'lead' => $service->summary, 'crumbs' => ['Services' => route('services.index'), $service->title => route('services.show', $service)]])

<section class="section">
    <div class="wrap split-layout">
        <div>
            @if ($service->image)
                <img src="{{ $service->mediaUrl() }}" alt="{{ $service->title }}" style="border-radius:14px;margin-bottom:40px" width="1200" height="675">
            @endif
            <div class="prose" data-reveal>{!! $body !!}</div>

            @if ($service->features)
                <h2 class="h-display" style="font-size:2.4rem;margin:64px 0 24px">What is included</h2>
                <div class="feature-grid">
                    @foreach ($service->features as $f)
                        <div class="feature" data-reveal><h3>@include('partials.icon', ['name' => 'check', 'size' => 20]){{ $f }}</h3></div>
                    @endforeach
                </div>
            @endif

            @if ($service->deliverables)
                <h2 class="h-display" style="font-size:2.4rem;margin:64px 0 24px">What you get at the end</h2>
                <ul class="check-list">
                    @foreach ($service->deliverables as $d)
                        <li data-reveal>@include('partials.icon', ['name' => 'check', 'size' => 18])<span>{{ $d }}</span></li>
                    @endforeach
                </ul>
            @endif
        </div>

        <aside class="aside-card">
            <h2>Talk to us about {{ \Illuminate\Support\Str::lower($service->title) }}</h2>
            <p>Tell us what you need. We reply within one working day.</p>
            <a href="{{ route('quote', ['service' => $service->title]) }}" class="btn btn-gold"><span>Start a project</span></a>
            <h3 style="font-size:1.2rem;margin-top:30px">Other services</h3>
            <ul class="aside-list">
                @foreach ($others as $o)
                    <li><a href="{{ route('services.show', $o) }}"><span>{{ $o->title }}</span>@include('partials.icon', ['name' => 'arrow', 'size' => 14])</a></li>
                @endforeach
            </ul>
        </aside>
    </div>
</section>

@if ($projects->isNotEmpty())
<section class="section" style="padding-top:0">
    <div class="wrap">
        <div class="section-head split"><h2 class="h-display">Recent work</h2><a href="{{ route('work.index') }}" class="link-arrow">All projects @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a></div>
        <div class="work-grid">
            @foreach ($projects as $project)
                @include('partials.project-card', ['project' => $project])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta')
@endsection
