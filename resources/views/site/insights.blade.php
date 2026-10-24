@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => $category ?: 'Insights', 'lead' => 'Practical writing on software, data and running a business with technology in Nigeria.', 'crumbs' => ['Insights' => route('insights.index')]])

<section class="section">
    <div class="wrap">
        <div class="filters">
            <a href="{{ route('insights.index') }}" @class(['chip', 'on' => ! $category])>All</a>
            @foreach ($categories as $c)
                <a href="{{ route('insights.index', ['category' => $c]) }}" @class(['chip', 'on' => $category === $c])>{{ $c }}</a>
            @endforeach
            <form class="search" role="search" action="{{ route('insights.index') }}">
                <label for="q" class="sr-only">Search articles</label>
                <input id="q" type="search" name="q" value="{{ $q }}" placeholder="Search articles">
                <button type="submit" aria-label="Search">@include('partials.icon', ['name' => 'search', 'size' => 18])</button>
            </form>
        </div>

        @if ($q)<p style="color:var(--ash);margin-bottom:30px">{{ $posts->total() }} {{ \Illuminate\Support\Str::plural('result', $posts->total()) }} for “{{ $q }}”.</p>@endif

        @if ($posts->isNotEmpty())
            <div class="post-grid">
                @foreach ($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
            {{ $posts->links() }}
        @else
            <p class="empty">No articles match that yet. <a href="{{ route('insights.index') }}">See all articles</a>.</p>
        @endif
    </div>
</section>
@endsection
