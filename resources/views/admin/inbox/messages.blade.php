@extends('layouts.admin')
@section('title', 'Messages')
@section('content')
<form class="toolbar" method="get">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search name, email or subject">
    <select name="filter" onchange="this.form.submit()"><option value="">All messages</option><option value="unread" @selected(request('filter') === 'unread')>Unread only</option></select>
    <button class="btn">Search</button>
</form>
<div class="table-wrap"><table class="table">
    <thead><tr><th>From</th><th>Subject</th><th>Received</th><th class="right">Actions</th></tr></thead>
    <tbody>
    @forelse ($items as $m)
        <tr @class(['row-unread' => ! $m->is_read])>
            <td><a href="{{ route('admin.messages.show', $m) }}" class="strong">{{ $m->name }}</a><br><small class="muted">{{ $m->email }}</small></td>
            <td>{{ $m->subject ?: \Illuminate\Support\Str::limit($m->message, 70) }}</td>
            <td class="nowrap">{{ $m->created_at->format('j M Y, g:ia') }}</td>
            <td class="right"><a href="{{ route('admin.messages.show', $m) }}" class="link">Open</a></td>
        </tr>
    @empty
        <tr><td colspan="4" class="empty-row">No messages yet. Messages sent from the contact page appear here.</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $items->links('admin.partials.pagination') }}
@endsection
