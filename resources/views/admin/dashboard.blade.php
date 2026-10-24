@extends('layouts.admin')
@section('title', 'Dashboard')
@section('actions')
    <a href="{{ route('admin.resource.create', 'posts') }}" class="btn">Write an article</a>
    <a href="{{ route('admin.resource.create', 'projects') }}" class="btn btn-primary">Add a project</a>
@endsection
@section('content')
<p class="greet">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}.</p>

<div class="kpis">
    <a href="{{ route('admin.messages', ['filter' => 'unread']) }}" class="kpi {{ $counts['unread'] ? 'kpi-hot' : '' }}"><span>Unread messages</span><strong>{{ $counts['unread'] }}</strong></a>
    <a href="{{ route('admin.quotes', ['status' => 'new']) }}" class="kpi {{ $counts['newQuotes'] ? 'kpi-hot' : '' }}"><span>New project enquiries</span><strong>{{ $counts['newQuotes'] }}</strong></a>
    <a href="{{ route('admin.subscribers') }}" class="kpi"><span>Subscribers</span><strong>{{ $counts['subscribers'] }}</strong></a>
    <a href="{{ route('admin.resource.index', 'posts') }}" class="kpi"><span>Articles</span><strong>{{ $counts['posts'] }}</strong></a>
</div>

<section class="panel">
    <h2>Enquiries in the last 14 days</h2>
    @php $max = max(1, $chart->max(fn ($d) => $d['messages'] + $d['quotes'])); @endphp
    <div class="bars" role="img" aria-label="Messages and project enquiries per day for the last 14 days">
        @foreach ($chart as $d)
            <div class="bar-col" title="{{ $d['label'] }}: {{ $d['messages'] }} messages, {{ $d['quotes'] }} enquiries">
                <div class="bar-stack">
                    <span class="bar bar-q" style="height: {{ $d['quotes'] / $max * 100 }}%"></span>
                    <span class="bar bar-m" style="height: {{ $d['messages'] / $max * 100 }}%"></span>
                </div>
                <small>{{ $d['label'] }}</small>
            </div>
        @endforeach
    </div>
    <p class="legend"><i class="sw sw-m"></i> Messages <i class="sw sw-q"></i> Project enquiries</p>
</section>

<div class="grid-2">
    <section class="panel">
        <div class="panel-head"><h2>Latest messages</h2><a href="{{ route('admin.messages') }}" class="link">All</a></div>
        <ul class="feed">
            @forelse ($latestMessages as $m)
                <li @class(['unread' => ! $m->is_read])>
                    <a href="{{ route('admin.messages.show', $m) }}"><strong>{{ $m->name }}</strong> <span>{{ $m->subject ?: \Illuminate\Support\Str::limit($m->message, 50) }}</span></a>
                    <time>{{ $m->created_at->diffForHumans() }}</time>
                </li>
            @empty
                <li class="muted">No messages yet. They arrive here from the contact page.</li>
            @endforelse
        </ul>
    </section>
    <section class="panel">
        <div class="panel-head"><h2>Latest project enquiries</h2><a href="{{ route('admin.quotes') }}" class="link">All</a></div>
        <ul class="feed">
            @forelse ($latestQuotes as $q)
                <li @class(['unread' => $q->status === 'new'])>
                    <a href="{{ route('admin.quotes.show', $q) }}"><strong>{{ $q->name }}</strong> <span>{{ $q->service ?: 'General enquiry' }}{{ $q->budget ? ', '.$q->budget : '' }}</span></a>
                    <time>{{ $q->created_at->diffForHumans() }}</time>
                </li>
            @empty
                <li class="muted">No enquiries yet. They arrive here from the Start a project page.</li>
            @endforelse
        </ul>
    </section>
</div>

<div class="grid-2">
    <section class="panel">
        <h2>Most read articles</h2>
        <ul class="feed">
            @forelse ($topPosts as $p)
                <li><a href="{{ route('admin.resource.edit', ['posts', $p->id]) }}">{{ $p->title }}</a><time>{{ number_format($p->views) }} views</time></li>
            @empty
                <li class="muted">No articles yet.</li>
            @endforelse
        </ul>
    </section>
    <section class="panel">
        <h2>Search engine checklist</h2>
        <ul class="checks">
            <li class="{{ \App\Models\Setting::get('google_verification') ? 'done' : '' }}"><a href="{{ route('admin.settings') }}#seo-and-tracking">Verify the site in Google Search Console</a></li>
            <li class="{{ \App\Models\Setting::get('google_analytics') ? 'done' : '' }}"><a href="{{ route('admin.settings') }}#seo-and-tracking">Add a Google Analytics ID</a></li>
            <li class="{{ \App\Models\Setting::get('phone') ? 'done' : '' }}"><a href="{{ route('admin.settings') }}">Add a phone number</a></li>
            <li class="{{ \App\Models\Testimonial::exists() ? 'done' : '' }}"><a href="{{ route('admin.resource.index', 'testimonials') }}">Add a client testimonial</a></li>
            <li class="{{ \App\Models\TeamMember::exists() ? 'done' : '' }}"><a href="{{ route('admin.resource.index', 'team') }}">Add your team</a></li>
            <li><a href="{{ route('sitemap') }}" target="_blank">Submit {{ route('sitemap') }} in Search Console</a></li>
        </ul>
    </section>
</div>
@endsection
