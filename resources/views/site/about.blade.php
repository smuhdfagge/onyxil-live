@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => 'A Kano technology company that stays after launch', 'lead' => $s['about_intro'] ?? null, 'crumbs' => ['About' => route('about')]])

<section class="section">
    <div class="wrap split-layout">
        <div class="prose" data-reveal>
            <h2 style="margin-top:0">Our story</h2>
            {!! $story !!}
        </div>
        <aside class="aside-card" data-reveal>
            <h2>Work with us</h2>
            <p>We take on a small number of projects at a time so each one gets proper attention.</p>
            <a href="{{ route('quote') }}" class="btn btn-gold"><span>Start a project</span></a>
            <a href="{{ route('careers.index') }}" class="btn btn-line"><span>Join the team</span></a>
        </aside>
    </div>
</section>

@if (! empty($s['mission']) || ! empty($s['vision']))
<section class="section" style="padding-top:0">
    <div class="wrap mv-grid">
        @if (! empty($s['mission']))<div class="mv" data-reveal><h2>Mission</h2><p>{{ $s['mission'] }}</p></div>@endif
        @if (! empty($s['vision']))<div class="mv" data-reveal><h2>Vision</h2><p>{{ $s['vision'] }}</p></div>@endif
    </div>
</section>
@endif

@if ($values)
<section class="section process">
    <div class="wrap">
        <div class="section-head"><h2 class="h-display">What we hold ourselves to</h2></div>
        <div class="values" style="border-color: rgba(26,21,13,.18)">
            @foreach ($values as [$v, $d])
                <div class="value" data-reveal style="border-color: rgba(26,21,13,.18)"><h3>{{ $v }}</h3><p style="color: var(--ink-soft)">{{ $d }}</p></div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($team->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head"><h2 class="h-display">The people</h2></div>
        <div class="team-grid">
            @foreach ($team as $m)
                <article class="member" data-reveal>
                    <div class="member-photo">
                        @if ($m->photo)
                            <img src="{{ $m->mediaUrl('photo') }}" alt="{{ $m->name }}" loading="lazy" width="480" height="600">
                        @else
                            <span class="member-initials" aria-hidden="true">{{ collect(explode(' ', $m->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}</span>
                        @endif
                    </div>
                    <h3>{{ $m->name }}</h3>
                    <p>{{ $m->role }}</p>
                    @if ($m->bio)<p>{{ $m->bio }}</p>@endif
                    @if ($m->linkedin)<a href="{{ $m->linkedin }}" class="link-arrow" target="_blank" rel="noopener">LinkedIn @include('partials.icon', ['name' => 'arrow-up-right', 'size' => 14])</a>@endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta')
@endsection
