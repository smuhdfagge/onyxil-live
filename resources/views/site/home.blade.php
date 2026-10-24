@extends('layouts.site')
@section('body_class', 'page-home')

@section('content')
{{-- Hero: the turning onyx stone is the one big moment on the site. --}}
<section class="hero" data-hero>
    <canvas class="hero-stone" data-stone aria-hidden="true"></canvas>
    <div class="hero-shade" aria-hidden="true"></div>
    <div class="wrap hero-inner">
        <p class="hero-kicker" data-load="1"><span class="dot" aria-hidden="true"></span>ICT company in Kano, working across Nigeria</p>
        <h1 class="hero-title" data-pour>{{ $s['hero_title'] ?? 'We build the systems behind the work.' }}</h1>
        <p class="hero-text" data-load="3">{{ $s['hero_text'] ?? '' }}</p>
        <div class="hero-actions" data-load="4">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg" data-magnetic><span>{{ $s['hero_primary'] ?? 'Start a project' }}</span>@include('partials.icon', ['name' => 'arrow', 'size' => 18])</a>
            <a href="{{ route('work.index') }}" class="btn btn-line btn-lg"><span>{{ $s['hero_secondary'] ?? 'See our work' }}</span></a>
        </div>
    </div>
    <div class="hero-foot wrap" data-load="5">
        <a href="#what-we-do" class="scroll-cue"><span class="scroll-line" aria-hidden="true"></span>Scroll to explore</a>
        @foreach ($products->take(1) as $p)
            <a href="{{ route('products.show', $p) }}" class="hero-product">
                <span class="hero-product-tag">{{ $p->status }}</span>
                <span><strong>{{ $p->title }}</strong> {{ $p->tagline }}</span>
                @include('partials.icon', ['name' => 'arrow-up-right', 'size' => 16])
            </a>
        @endforeach
    </div>
</section>

{{-- Capability ticker --}}
<div class="ticker" aria-hidden="true">
    <div class="ticker-track">
        @for ($i = 0; $i < 2; $i++)
            @foreach (['Custom software', 'Websites', 'Mobile apps', 'Cloud hosting', 'MIS and dashboards', 'Networks', 'Cybersecurity', 'IT advisory', 'Accounting software'] as $t)
                <span>{{ $t }}</span><i>◆</i>
            @endforeach
        @endfor
    </div>
</div>

{{-- Intro statement --}}
<section class="section intro" id="what-we-do">
    <div class="wrap intro-grid">
        <h2 class="statement" data-words>{{ $s['about_intro'] ?? '' }}</h2>
        <div class="intro-side" data-reveal>
            @if ($stats)
                <dl class="stats">
                    @foreach ($stats as [$figure, $label])
                        <div><dt data-count="{{ $figure }}">{{ $figure }}</dt><dd>{{ $label }}</dd></div>
                    @endforeach
                </dl>
            @endif
            <a href="{{ route('about') }}" class="link-arrow">More about Onyx @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a>
        </div>
    </div>
</section>

{{-- Services index --}}
@if ($services->isNotEmpty())
<section class="section services-home">
    <div class="wrap">
        <div class="section-head">
            <h2 class="h-display">What we do</h2>
            <p>Most projects start with one of these and grow into two or three. We handle them together so nothing falls between suppliers.</p>
        </div>
        <ul class="svc-list">
            @foreach ($services as $sv)
                <li data-reveal>
                    <a href="{{ route('services.show', $sv) }}" class="svc-row">
                        <span class="svc-icon">@include('partials.icon', ['name' => $sv->icon, 'size' => 28, 'stroke' => 1.4])</span>
                        <span class="svc-title">{{ $sv->title }}</span>
                        <span class="svc-sum">{{ $sv->summary }}</span>
                        <span class="svc-go" aria-hidden="true">@include('partials.icon', ['name' => 'arrow-up-right', 'size' => 22])</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- Product spotlight: MyBooks --}}
@foreach ($products as $p)
<section class="section product-spot">
    <div class="wrap product-grid">
        <div class="product-copy" data-reveal>
            <p class="product-badge"><span class="dot"></span>Built and run by Onyx, {{ strtolower($p->status) }} now</p>
            <h2 class="h-display">{{ $p->title }}</h2>
            <p class="product-tagline">{{ $p->tagline }}</p>
            <p>{{ $p->summary }}</p>
            <ul class="ticks">
                @foreach (collect($p->features)->take(5) as $f)
                    @php [$ft, $fd] = array_pad(array_map('trim', explode('|', $f, 2)), 2, null); @endphp
                    <li>@include('partials.icon', ['name' => 'check', 'size' => 18])<span><strong>{{ $ft }}.</strong> {{ $fd }}</span></li>
                @endforeach
            </ul>
            <div class="btn-row">
                <a href="{{ route('products.show', $p) }}" class="btn btn-gold"><span>Explore {{ $p->title }}</span></a>
                @if ($p->url)<a href="{{ $p->url }}" class="btn btn-line" target="_blank" rel="noopener"><span>{{ parse_url($p->url, PHP_URL_HOST) }}</span>@include('partials.icon', ['name' => 'external', 'size' => 16])</a>@endif
            </div>
        </div>
        @include('partials.ledger', ['product' => $p])
    </div>
</section>
@endforeach

{{-- How we work: a real sequence, so it is numbered --}}
<section class="section process">
    <div class="wrap">
        <div class="section-head">
            <h2 class="h-display">How a project runs</h2>
            <p>The same five stages whether we are building a website or a system used across a state.</p>
        </div>
        <ol class="steps" data-steps>
            @foreach ([
                ['Understand', 'We sit with the people who will use the system and write down how the work really happens today.'],
                ['Plan', 'You get a written scope, a fixed price or a clear range, and a timeline you can hold us to.'],
                ['Build in stages', 'Working software every two weeks, so you can see progress and change direction early.'],
                ['Launch', 'We move your data across, train your team and stay close for the first busy weeks.'],
                ['Stay on', 'Hosting, fixes and improvements. The work does not end when the system goes live.'],
            ] as $i => [$t, $d])
                <li class="step" data-reveal style="--i: {{ $i }}">
                    <span class="step-n">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $t }}</h3>
                    <p>{{ $d }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- Selected work --}}
@if ($projects->isNotEmpty())
<section class="section work-home">
    <div class="wrap">
        <div class="section-head split">
            <h2 class="h-display">Selected work</h2>
            <a href="{{ route('work.index') }}" class="link-arrow">All projects @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a>
        </div>
        <div class="work-grid">
            @foreach ($projects as $project)
                @include('partials.project-card', ['project' => $project, 'wide' => $loop->first])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials appear only when real ones are added in the admin --}}
@if ($testimonials->isNotEmpty())
<section class="section quotes">
    <div class="wrap">
        <h2 class="sr-only">What clients say</h2>
        <div class="quote-slider" data-slider>
            @foreach ($testimonials as $t)
                <figure class="quote-slide" @if(! $loop->first) hidden @endif>
                    <blockquote>{{ $t->quote }}</blockquote>
                    <figcaption>
                        @if ($t->photo)<img src="{{ $t->mediaUrl('photo') }}" alt="" width="56" height="56" loading="lazy">@endif
                        <span><strong>{{ $t->name }}</strong>{{ collect([$t->role, $t->company])->filter()->implode(', ') }}</span>
                    </figcaption>
                </figure>
            @endforeach
            @if ($testimonials->count() > 1)
                <div class="quote-nav">
                    <button type="button" data-prev aria-label="Previous testimonial">@include('partials.icon', ['name' => 'arrow', 'size' => 18, 'class' => 'flip'])</button>
                    <button type="button" data-next aria-label="Next testimonial">@include('partials.icon', ['name' => 'arrow', 'size' => 18])</button>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

@if ($clients->isNotEmpty())
<section class="clients-strip" aria-label="Organisations we work with">
    <div class="wrap clients-row">
        @foreach ($clients as $c)
            @if ($c->logo)
                <img src="{{ $c->mediaUrl('logo') }}" alt="{{ $c->name }}" loading="lazy" height="44">
            @else
                <span>{{ $c->name }}</span>
            @endif
        @endforeach
    </div>
</section>
@endif

{{-- Insights --}}
@if ($posts->isNotEmpty())
<section class="section insights-home">
    <div class="wrap">
        <div class="section-head split">
            <h2 class="h-display">From the notebook</h2>
            <a href="{{ route('insights.index') }}" class="link-arrow">All insights @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a>
        </div>
        <div class="post-grid">
            @foreach ($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($faqs->isNotEmpty())
<section class="section faq-home">
    <div class="wrap faq-grid">
        <div class="section-head">
            <h2 class="h-display">Questions people ask us</h2>
            <p>Something else on your mind? <a href="{{ route('contact') }}">Ask us directly</a>.</p>
        </div>
        @include('partials.faq', ['faqs' => $faqs])
    </div>
</section>
@endif

@include('partials.cta')
@endsection
