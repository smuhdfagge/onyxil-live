@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => $project->title, 'lead' => $project->summary, 'crumbs' => ['Work' => route('work.index'), $project->title => route('work.show', $project)]])

<section class="section section-tight">
    <div class="wrap">
        <div class="article-cover" data-reveal>
            @if ($project->image)
                <img src="{{ $project->mediaUrl() }}" alt="{{ $project->title }}" width="1600" height="686">
            @else
                <x-cover :seed="$project->slug" :label="$project->title" />
            @endif
        </div>
        <dl class="facts">
            @if ($project->client)<div><dt>Client</dt><dd>{{ $project->client }}</dd></div>@endif
            @if ($project->industry)<div><dt>Industry</dt><dd>{{ $project->industry }}</dd></div>@endif
            @if ($project->year)<div><dt>Year</dt><dd>{{ $project->year }}</dd></div>@endif
            @if ($project->url)<div><dt>Live link</dt><dd><a href="{{ $project->url }}" target="_blank" rel="noopener">{{ parse_url($project->url, PHP_URL_HOST) }}</a></dd></div>@endif
        </dl>

        @foreach (['The problem' => $challenge, 'What we built' => $solution, 'Outcome' => $results] as $h => $html)
            @if ($html)
                <div class="case-section" data-reveal>
                    <h2>{{ $h }}</h2>
                    <div class="prose">{!! $html !!}</div>
                </div>
            @endif
        @endforeach

        @if ($project->stack)
            <div class="case-section" data-reveal>
                <h2>Technology</h2>
                <ul class="chips">@foreach ($project->stack as $t)<li>{{ $t }}</li>@endforeach</ul>
            </div>
        @endif
    </div>
</section>

@if ($next && $next->id !== $project->id)
<a href="{{ route('work.show', $next) }}" class="next-project">
    <div class="wrap"><span>Next project</span><strong>{{ $next->title }}</strong></div>
</a>
@endif
@include('partials.cta')
@endsection
