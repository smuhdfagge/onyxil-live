@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => 'Start a project', 'lead' => 'Tell us what you want to build. Within two working days we will come back with questions, a rough plan and a cost range.', 'crumbs' => ['Start a project' => route('quote')]])

<section class="section">
    <div class="wrap form-layout">
        <div>
            @if (session('sent'))
                <div class="notice" role="status"><strong>Thank you, we have your project details.</strong><p>Someone from our team will email you within two working days. If it is urgent, call or WhatsApp us.</p></div>
            @endif
            <form method="post" action="{{ route('quote.send') }}" class="form" novalidate>
                @csrf
                <input type="hidden" name="_started" value="">
                <input type="text" name="website_url" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                @include('partials.field', ['name' => 'name', 'label' => 'Your name', 'required' => true, 'autocomplete' => 'name'])
                @include('partials.field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'autocomplete' => 'email'])
                @include('partials.field', ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'autocomplete' => 'tel'])
                @include('partials.field', ['name' => 'company', 'label' => 'Organisation', 'autocomplete' => 'organization'])
                @include('partials.field', ['name' => 'service', 'label' => 'What do you need?', 'type' => 'select', 'options' => $services->push('Something else')->all(), 'full' => true])
                @include('partials.field', ['name' => 'budget', 'label' => 'Rough budget', 'type' => 'chips', 'options' => $budgets, 'full' => true])
                @include('partials.field', ['name' => 'timeline', 'label' => 'When do you need it?', 'type' => 'chips', 'options' => $timelines, 'full' => true])
                @include('partials.field', ['name' => 'details', 'label' => 'Tell us about the project', 'type' => 'textarea', 'required' => true, 'full' => true, 'hint' => 'What problem are you trying to solve? Who will use it? Is anything already in place?'])
                <div class="field full"><button type="submit" class="btn btn-gold btn-lg" data-magnetic><span>Send project details</span>@include('partials.icon', ['name' => 'arrow', 'size' => 18])</button></div>
            </form>
        </div>
        <aside class="aside-card">
            <h2>What happens next</h2>
            <ol class="check-list" style="list-style:none">
                <li>@include('partials.icon', ['name' => 'check', 'size' => 18])<span>We read your details and reply with any questions.</span></li>
                <li>@include('partials.icon', ['name' => 'check', 'size' => 18])<span>A short call to understand the work, usually 30 minutes.</span></li>
                <li>@include('partials.icon', ['name' => 'check', 'size' => 18])<span>A written plan with scope, timeline and cost.</span></li>
                <li>@include('partials.icon', ['name' => 'check', 'size' => 18])<span>If it is a fit, we agree terms and start.</span></li>
            </ol>
            <p style="margin-top:22px">Not ready for a project yet? <a href="{{ route('contact') }}">Send us a question instead</a>.</p>
        </aside>
    </div>
</section>
@endsection
