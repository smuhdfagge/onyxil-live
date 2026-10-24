@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => 'Talk to us', 'lead' => 'Questions, a project, a system that needs fixing or a demo of MyBooks. We reply within one working day.', 'crumbs' => ['Contact' => route('contact')]])

<section class="section">
    <div class="wrap form-layout">
        <div>
            @if (session('sent'))
                <div class="notice" role="status"><strong>Message sent.</strong><p>Thank you. We will reply to you by email within one working day.</p></div>
            @endif
            <form method="post" action="{{ route('contact.send') }}" class="form" novalidate>
                @csrf
                <input type="hidden" name="_started" value="">
                <input type="text" name="website_url" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                @include('partials.field', ['name' => 'name', 'label' => 'Your name', 'required' => true, 'autocomplete' => 'name'])
                @include('partials.field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'autocomplete' => 'email'])
                @include('partials.field', ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'autocomplete' => 'tel'])
                @include('partials.field', ['name' => 'company', 'label' => 'Organisation', 'autocomplete' => 'organization'])
                @include('partials.field', ['name' => 'subject', 'label' => 'Subject', 'full' => true])
                @include('partials.field', ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => true, 'full' => true])
                <div class="field full"><button type="submit" class="btn btn-primary btn-lg">Send message @include('partials.icon', ['name' => 'arrow', 'size' => 18])</button></div>
                <p class="field full form-note">We only use your details to reply to you. See our <a href="{{ url('privacy') }}">privacy policy</a>.</p>
            </form>
        </div>

        <aside>
            <ul class="contact-list">
                @if (! empty($s['email']))<li><span class="ci">@include('partials.icon', ['name' => 'mail', 'size' => 20])</span><div><small>Email</small><a href="mailto:{{ $s['email'] }}">{{ $s['email'] }}</a></div></li>@endif
                @if (! empty($s['phone']))<li><span class="ci">@include('partials.icon', ['name' => 'phone', 'size' => 20])</span><div><small>Phone</small><a href="tel:{{ preg_replace('/\s+/', '', $s['phone']) }}">{{ $s['phone'] }}</a></div></li>@endif
                @if (! empty($s['whatsapp']))<li><span class="ci">@include('partials.icon', ['name' => 'whatsapp', 'size' => 20])</span><div><small>WhatsApp</small><a href="https://wa.me/{{ preg_replace('/\D/', '', $s['whatsapp']) }}" target="_blank" rel="noopener">Chat with us</a></div></li>@endif
                @if (! empty($s['address']))<li><span class="ci">@include('partials.icon', ['name' => 'pin', 'size' => 20])</span><div><small>Office</small><span>{!! nl2br(e($s['address'])) !!}</span></div></li>@endif
                @if (! empty($s['hours']))<li><span class="ci">@include('partials.icon', ['name' => 'clock', 'size' => 20])</span><div><small>Hours</small><span>{{ $s['hours'] }}</span></div></li>@endif
            </ul>
            @if (! empty($s['map_query']))
                <div class="map"><iframe title="Map showing our office" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q={{ urlencode($s['map_query']) }}&z=14&output=embed"></iframe></div>
            @endif
        </aside>
    </div>
</section>
@endsection
