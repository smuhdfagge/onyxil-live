@extends('layouts.site')
@section('body_class', 'page-home')

@section('content')
{{-- Hero: plain copy on the left, the faceted onyx gem in a navy panel on the right --}}
<section class="hero">
    <div class="wrap hero-grid">
        <div class="hero-copy">
            <h1 class="hero-title" data-load="2">{{ $s['hero_title'] ?? 'We build the systems behind the work.' }}</h1>
            <p class="hero-text" data-load="3">{{ $s['hero_text'] ?? '' }}</p>
            <div class="btn-row" data-load="4">
                <a href="{{ route('quote') }}" class="btn btn-primary btn-lg">{{ $s['hero_primary'] ?? 'Start a project' }} @include('partials.icon', ['name' => 'arrow', 'size' => 18])</a>
                <a href="{{ route('work.index') }}" class="btn btn-outline btn-lg">{{ $s['hero_secondary'] ?? 'See our work' }}</a>
            </div>
            <ul class="hero-points" data-load="5">
                <li>@include('partials.icon', ['name' => 'check', 'size' => 16])You own the code and the data</li>
                <li>@include('partials.icon', ['name' => 'check', 'size' => 16])Support after launch included</li>
                <li>@include('partials.icon', ['name' => 'check', 'size' => 16])Reply within one working day</li>
            </ul>
        </div>

        <div class="hero-visual" data-load="3">
            <canvas class="hero-stone" data-stone aria-hidden="true"></canvas>
            <div class="hero-chip chip-a">@include('partials.icon', ['name' => 'code', 'size' => 16]) Custom software</div>
            <div class="hero-chip chip-b">@include('partials.icon', ['name' => 'cloud', 'size' => 16]) Cloud hosting</div>
            <div class="hero-chip chip-c">@include('partials.icon', ['name' => 'chart', 'size' => 16]) MIS and dashboards</div>
            @foreach ($products->take(1) as $p)
                <a href="{{ route('products.show', $p) }}" class="hero-product">
                    <span class="tag">{{ $p->status }}</span>
                    <span><strong>{{ $p->title }}</strong> {{ $p->tagline }}</span>
                    @include('partials.icon', ['name' => 'arrow-up-right', 'size' => 16])
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- About in brief --}}
<section class="section section-tight intro">
    <div class="wrap intro-grid">
        <div data-reveal>
            <h2 class="h2">Who we are</h2>
            <p class="lead">{{ $s['about_intro'] ?? '' }}</p>
            <a href="{{ route('about') }}" class="link-arrow">More about Onyx @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a>
        </div>
        @if ($stats)
            <dl class="stats" data-reveal>
                @foreach ($stats as [$figure, $label])
                    <div><dt data-count="{{ $figure }}">{{ $figure }}</dt><dd>{{ $label }}</dd></div>
                @endforeach
            </dl>
        @else
            <ul class="pillars" data-reveal>
                <li><span class="pillar-icon">@include('partials.icon', ['name' => 'compass', 'size' => 20])</span><div><strong>Plan</strong><p>We learn how your team works before we design anything.</p></div></li>
                <li><span class="pillar-icon">@include('partials.icon', ['name' => 'code', 'size' => 20])</span><div><strong>Build</strong><p>Standard, well-supported tools. Working software every two weeks.</p></div></li>
                <li><span class="pillar-icon">@include('partials.icon', ['name' => 'shield', 'size' => 20])</span><div><strong>Run</strong><p>Hosting, backups, fixes and improvements after launch.</p></div></li>
            </ul>
        @endif
    </div>
</section>

{{-- Services --}}
@if ($services->isNotEmpty())
<section class="section section-alt" id="what-we-do">
    <div class="wrap">
        <div class="section-head split">
            <div>
                <h2 class="h2">What we do</h2>
                <p>Most projects start with one of these and grow into two or three. We handle them together so nothing falls between suppliers.</p>
            </div>
            <a href="{{ route('services.index') }}" class="link-arrow">All services @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a>
        </div>
        <div class="card-grid cols-4">
            @foreach ($services as $sv)
                <a href="{{ route('services.show', $sv) }}" class="card svc-card" data-reveal>
                    <span class="card-icon">@include('partials.icon', ['name' => $sv->icon, 'size' => 22])</span>
                    <h3>{{ $sv->title }}</h3>
                    <p>{{ $sv->summary }}</p>
                    <span class="card-more">Learn more @include('partials.icon', ['name' => 'arrow', 'size' => 14])</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Product spotlight: MyBooks --}}
@foreach ($products as $p)
<section class="section band-navy product-spot">
    <div class="wrap product-grid">
        <div class="product-copy" data-reveal>
            <p class="eyebrow-pill on-dark"><span class="dot" aria-hidden="true"></span>Our product, {{ strtolower($p->status) }} now</p>
            <h2 class="h2">{{ $p->title }}</h2>
            <p class="product-tagline">{{ $p->tagline }}</p>
            <p>{{ $p->summary }}</p>
            <ul class="ticks">
                @foreach (collect($p->features)->take(5) as $f)
                    @php [$ft, $fd] = array_pad(array_map('trim', explode('|', $f, 2)), 2, null); @endphp
                    <li>@include('partials.icon', ['name' => 'check', 'size' => 16])<span><strong>{{ $ft }}.</strong> {{ $fd }}</span></li>
                @endforeach
            </ul>
            <div class="btn-row">
                <a href="{{ route('products.show', $p) }}" class="btn btn-accent">Explore {{ $p->title }}</a>
                @if ($p->url)<a href="{{ $p->url }}" class="btn btn-outline" target="_blank" rel="noopener">{{ parse_url($p->url, PHP_URL_HOST) }} @include('partials.icon', ['name' => 'external', 'size' => 15])</a>@endif
            </div>
        </div>
        @include('partials.ledger', ['product' => $p])
    </div>
</section>
@endforeach

{{-- How we work: a real sequence, so it is numbered --}}
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <h2 class="h2">How a project runs</h2>
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
                <li class="step" data-reveal>
                    <span class="step-n">{{ $i + 1 }}</span>
                    <h3>{{ $t }}</h3>
                    <p>{{ $d }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- Selected work --}}
@if ($projects->isNotEmpty())
<section class="section section-alt">
    <div class="wrap">
        <div class="section-head split">
            <h2 class="h2">Selected work</h2>
            <a href="{{ route('work.index') }}" class="link-arrow">All projects @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a>
        </div>
        <div class="card-grid cols-3">
            @foreach ($projects->take(3) as $project)
                @include('partials.project-card', ['project' => $project])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials appear only when real ones are added in the admin --}}
@if ($testimonials->isNotEmpty())
<section class="section">
    <div class="wrap">
        <h2 class="sr-only">What clients say</h2>
        <div class="quote-slider" data-slider>
            @foreach ($testimonials as $t)
                <figure class="quote-slide" @if(! $loop->first) hidden @endif>
                    <blockquote>{{ $t->quote }}</blockquote>
                    <figcaption>
                        @if ($t->photo)<img src="{{ $t->mediaUrl('photo') }}" alt="" width="48" height="48" loading="lazy">@endif
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
                <img src="{{ $c->mediaUrl('logo') }}" alt="{{ $c->name }}" loading="lazy" height="40">
            @else
                <span>{{ $c->name }}</span>
            @endif
        @endforeach
    </div>
</section>
@endif

{{-- Insights --}}
@if ($posts->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head split">
            <h2 class="h2">Latest insights</h2>
            <a href="{{ route('insights.index') }}" class="link-arrow">All insights @include('partials.icon', ['name' => 'arrow', 'size' => 16])</a>
        </div>
        <div class="card-grid cols-3">
            @foreach ($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($faqs->isNotEmpty())
<section class="section section-alt">
    <div class="wrap faq-grid">
        <div class="section-head">
            <h2 class="h2">Common questions</h2>
            <p>Something else on your mind? <a href="{{ route('contact') }}">Ask us directly</a>.</p>
        </div>
        @include('partials.faq', ['faqs' => $faqs])
    </div>
</section>
@endif

@include('partials.cta')
@endsection
