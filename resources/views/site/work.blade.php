@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => 'Things we have built and launched', 'lead' => 'A selection of websites, products and systems. Each one is still running.', 'crumbs' => ['Work' => route('work.index')]])

<section class="section">
    <div class="wrap">
        @if ($industries->count() > 1)
            <div class="filters" role="group" aria-label="Filter by industry" data-filter>
                <button type="button" class="chip on" data-f="*">All</button>
                @foreach ($industries as $ind)
                    <button type="button" class="chip" data-f="{{ \Illuminate\Support\Str::slug($ind) }}">{{ $ind }}</button>
                @endforeach
            </div>
        @endif
        <div class="work-grid">
            @forelse ($projects as $project)
                <div data-cat="{{ \Illuminate\Support\Str::slug($project->industry) }}" @class(['is-wide' => $loop->first]) style="{{ $loop->first ? 'grid-column:1/-1' : '' }}">
                    @include('partials.project-card', ['project' => $project, 'wide' => $loop->first])
                </div>
            @empty
                <p class="empty">Projects will appear here soon.</p>
            @endforelse
        </div>
    </div>
</section>
@include('partials.cta')
@push('scripts')
<script>
document.querySelectorAll('[data-filter] .chip').forEach(function (b) {
    b.addEventListener('click', function () {
        document.querySelectorAll('[data-filter] .chip').forEach(function (c) { c.classList.toggle('on', c === b); });
        var f = b.dataset.f;
        document.querySelectorAll('[data-cat]').forEach(function (el) { el.hidden = !(f === '*' || el.dataset.cat === f); });
    });
});
</script>
@endpush
@endsection
