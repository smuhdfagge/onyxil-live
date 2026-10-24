@extends('layouts.admin')
@section('title', 'Enquiry from '.$quote->name)
@section('actions')<a href="{{ route('admin.quotes') }}" class="btn btn-ghost">← All enquiries</a>@endsection
@section('content')
<div class="grid-2 grid-wide">
    <section class="panel">
        <dl class="dl">
            <dt>From</dt><dd>{{ $quote->name }}</dd>
            <dt>Email</dt><dd><a href="mailto:{{ $quote->email }}">{{ $quote->email }}</a></dd>
            @if ($quote->phone)<dt>Phone</dt><dd><a href="tel:{{ $quote->phone }}">{{ $quote->phone }}</a></dd>@endif
            @if ($quote->company)<dt>Organisation</dt><dd>{{ $quote->company }}</dd>@endif
            <dt>Service</dt><dd>{{ $quote->service ?: '—' }}</dd>
            <dt>Budget</dt><dd>{{ $quote->budget ?: '—' }}</dd>
            <dt>Timeline</dt><dd>{{ $quote->timeline ?: '—' }}</dd>
            <dt>Received</dt><dd>{{ $quote->created_at->format('l j F Y, g:ia') }}</dd>
        </dl>
        <h2 style="margin-top:24px">Project details</h2>
        <div class="message-body">{!! nl2br(e($quote->details)) !!}</div>
        <div class="btn-row">
            <a class="btn btn-primary" href="mailto:{{ $quote->email }}?subject={{ rawurlencode('Your project enquiry with Onyx Innovations') }}">Reply by email</a>
            @if ($quote->phone)<a class="btn" href="https://wa.me/{{ preg_replace('/\D/', '', $quote->phone) }}" target="_blank" rel="noopener">WhatsApp</a>@endif
        </div>
    </section>
    <section class="panel">
        <h2>Follow-up</h2>
        <form method="post" action="{{ route('admin.quotes.update', $quote) }}" class="fields">
            @csrf @method('put')
            <div class="fld fld-wide"><label for="status">Status</label>
                <select id="status" name="status">@foreach ($statuses as $k => $v)<option value="{{ $k }}" @selected($quote->status === $k)>{{ $v }}</option>@endforeach</select>
            </div>
            <div class="fld fld-wide"><label for="notes">Private notes</label><textarea id="notes" name="notes" rows="8" placeholder="Calls, next steps, figures discussed…">{{ old('notes', $quote->notes) }}</textarea></div>
            <div class="fld fld-wide"><button class="btn btn-primary">Save</button></div>
        </form>
        <form method="post" action="{{ route('admin.quotes.destroy', $quote) }}" data-confirm="Delete this enquiry?" style="margin-top:24px">@csrf @method('delete')<button class="btn btn-danger">Delete enquiry</button></form>
    </section>
</div>
@endsection
