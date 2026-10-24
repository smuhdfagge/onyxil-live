<article class="work-card {{ $wide ?? false ? 'is-wide' : '' }}" data-reveal data-tilt>
    <a href="{{ route('work.show', $project) }}" class="work-card-link">
        <div class="work-card-media">
            @if ($project->image)
                <img src="{{ $project->mediaUrl() }}" alt="{{ $project->title }}" loading="lazy" decoding="async" width="1200" height="750">
            @else
                <x-cover :seed="$project->slug" :label="$project->title" />
            @endif
            <span class="work-card-view" aria-hidden="true">View project</span>
        </div>
        <div class="work-card-body">
            <p class="meta">{{ collect([$project->industry, $project->year])->filter()->implode(', ') }}</p>
            <h3>{{ $project->title }}</h3>
            <p>{{ $project->summary }}</p>
        </div>
    </a>
</article>
