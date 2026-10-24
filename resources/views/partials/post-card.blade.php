<article class="post-card" data-reveal>
    <a href="{{ route('insights.show', $post) }}" class="post-card-media" tabindex="-1" aria-hidden="true">
        @if ($post->image)
            <img src="{{ $post->mediaUrl() }}" alt="" loading="lazy" decoding="async" width="800" height="500">
        @else
            <x-cover :seed="$post->slug" />
        @endif
    </a>
    <div class="post-card-body">
        <p class="meta">
            @if ($post->category)<span>{{ $post->category }}</span>@endif
            <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at ?? $post->created_at)->format('j M Y') }}</time>
        </p>
        <h3><a href="{{ route('insights.show', $post) }}">{{ $post->title }}</a></h3>
        <p>{{ $post->excerpt }}</p>
    </div>
</article>
