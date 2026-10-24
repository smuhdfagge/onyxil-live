@extends('layouts.admin')
@section('title', 'Message from '.$message->name)
@section('actions')<a href="{{ route('admin.messages') }}" class="btn btn-ghost">← All messages</a>@endsection
@section('content')
<section class="panel">
    <dl class="dl">
        <dt>From</dt><dd>{{ $message->name }}</dd>
        <dt>Email</dt><dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
        @if ($message->phone)<dt>Phone</dt><dd><a href="tel:{{ $message->phone }}">{{ $message->phone }}</a></dd>@endif
        @if ($message->company)<dt>Organisation</dt><dd>{{ $message->company }}</dd>@endif
        @if ($message->subject)<dt>Subject</dt><dd>{{ $message->subject }}</dd>@endif
        <dt>Received</dt><dd>{{ $message->created_at->format('l j F Y, g:ia') }}</dd>
    </dl>
    <div class="message-body">{!! nl2br(e($message->message)) !!}</div>
    <div class="btn-row">
        <a class="btn btn-primary" href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Your message to Onyx Innovations')) }}">Reply by email</a>
        @if ($message->phone)<a class="btn" href="https://wa.me/{{ preg_replace('/\D/', '', $message->phone) }}" target="_blank" rel="noopener">WhatsApp</a>@endif
        <form method="post" action="{{ route('admin.messages.unread', $message) }}" class="inline">@csrf<button class="btn btn-ghost">Mark as unread</button></form>
        <form method="post" action="{{ route('admin.messages.destroy', $message) }}" class="inline" data-confirm="Delete this message?">@csrf @method('delete')<button class="btn btn-danger">Delete</button></form>
    </div>
</section>
@endsection
