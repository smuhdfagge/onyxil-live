<article class="card work-card" data-reveal>
    <a href="{{ route('work.show', $project) }}" class="work-card-media" tabindex="-1" aria-hidden="true">
        @if ($project->image)
            <img src="{{ $project->mediaUrl() }}" alt="" loading="lazy" decoding="async" width="800" height="500">
        @else
            <x-cover :seed="$project->slug" :label="$project->title" />
        @endif
    </a>
    <div class="card-body">
        <p class="meta">{{ collect([$project->industry, $project->year])->filter()->implode(', ') }}</p>
        <h3><a href="{{ route('work.show', $project) }}">{{ $project->title }}</a></h3>
        <p>{{ $project->summary }}</p>
    </div>
</article>
