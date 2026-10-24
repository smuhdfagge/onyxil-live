@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => 'Build things people rely on', 'lead' => 'We are a small team building software that organisations depend on every day. We hire people who care about getting the details right.', 'crumbs' => ['Careers' => route('careers.index')]])

<section class="section">
    <div class="wrap">
        <div class="section-head"><h2 class="h2">Open roles</h2></div>
        @if ($jobs->isNotEmpty())
            <ul class="jobs">
                @foreach ($jobs as $job)
                    <li data-reveal>
                        <a href="{{ route('careers.show', $job) }}" class="job-row">
                            <div><h2>{{ $job->title }}</h2><p>{{ $job->summary }}</p></div>
                            <span class="chip">{{ $job->type }}</span>
                            <span class="chip">{{ $job->location }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="empty">There are no open roles right now. You can still send your CV to {{ $s['email'] ?? 'us' }} and we will keep it on file.</p>
        @endif
    </div>
</section>
@include('partials.cta')
@endsection
