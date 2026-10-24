@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => $job->title, 'lead' => $job->summary, 'crumbs' => ['Careers' => route('careers.index'), $job->title => route('careers.show', $job)]])
<section class="section">
    <div class="wrap split-layout">
        <div class="prose">{!! $body !!}</div>
        <aside class="aside-card">
            <h2>At a glance</h2>
            <dl class="facts" style="grid-template-columns:1fr;border:0">
                <div style="padding:10px 0"><dt>Type</dt><dd>{{ $job->type }}</dd></div>
                <div style="padding:10px 0"><dt>Location</dt><dd>{{ $job->location }}</dd></div>
                @if ($job->closes_at)<div style="padding:10px 0"><dt>Closing date</dt><dd>{{ $job->closes_at->format('j F Y') }}</dd></div>@endif
            </dl>
            @if (! empty($s['email']))
                <a href="mailto:{{ $s['email'] }}?subject={{ rawurlencode('Application: '.$job->title) }}" class="btn btn-gold"><span>Apply by email</span></a>
            @endif
        </aside>
    </div>
</section>
@endsection
