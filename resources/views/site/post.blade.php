@extends('layouts.site')
@section('content')
<section class="page-hero">
    <div class="page-hero-art" aria-hidden="true"><canvas data-lattice data-density="0.4"></canvas></div>
    <div class="wrap article-head">
        <nav class="crumbs" aria-label="Breadcrumb"><ol><li><a href="{{ route('home') }}">Home</a></li><li><a href="{{ route('insights.index') }}">Insights</a></li>@if($post->category)<li><a href="{{ route('insights.index', ['category' => $post->category]) }}">{{ $post->category }}</a></li>@endif</ol></nav>
        <h1 class="page-title article-title">{{ $post->title }}</h1>
        <p class="meta article-meta">
            @if ($post->author)<span>{{ $post->author }}</span>@endif
            <time datetime="{{ optional($post->published_at)->toAtomString() }}">{{ optional($post->published_at ?? $post->created_at)->format('j F Y') }}</time>
            <span>{{ $post->readingMinutes() }} min read</span>
        </p>
    </div>
</section>

<article class="section section-tight">
    <div class="wrap">
        @if ($post->image)
            <div class="article-cover"><img src="{{ $post->mediaUrl() }}" alt="{{ $post->title }}" width="1600" height="686"></div>
        @endif
        <div class="article-layout">
            <aside>
                @if (count($toc) > 1)
                    <nav class="toc" aria-label="On this page">
                        <h2>On this page</h2>
                        <ol>@foreach ($toc as $h)<li><a href="#{{ $h['id'] }}">{{ $h['text'] }}</a></li>@endforeach</ol>
                    </nav>
                @endif
            </aside>
            <div>
                @if ($post->excerpt)<p class="article-lead">{{ $post->excerpt }}</p>@endif
                <div class="prose">{!! $body !!}</div>
                @php $u = urlencode(route('insights.show', $post)); $t = urlencode($post->title); @endphp
                <div class="share">
                    <a class="chip" href="https://wa.me/?text={{ $t }}%20{{ $u }}" target="_blank" rel="noopener">Share on WhatsApp</a>
                    <a class="chip" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $u }}" target="_blank" rel="noopener">LinkedIn</a>
                    <a class="chip" href="https://x.com/intent/post?url={{ $u }}&text={{ $t }}" target="_blank" rel="noopener">X</a>
                    <button type="button" class="chip" data-copy="{{ route('insights.show', $post) }}"><span>Copy link</span></button>
                </div>
            </div>
        </div>
    </div>
</article>

@if ($related->isNotEmpty())
<section class="section pt-0">
    <div class="wrap">
        <div class="section-head"><h2 class="h2">Keep reading</h2></div>
        <div class="card-grid cols-3">
            @foreach ($related as $r)
                @include('partials.post-card', ['post' => $r])
            @endforeach
        </div>
    </div>
</section>
@endif
@include('partials.cta')
@endsection
