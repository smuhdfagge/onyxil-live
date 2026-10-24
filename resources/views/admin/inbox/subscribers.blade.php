@extends('layouts.admin')
@section('title', 'Subscribers')
@section('actions')<a href="{{ route('admin.subscribers.export') }}" class="btn btn-primary">Download CSV</a>@endsection
@section('content')
<form class="toolbar" method="get">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search email">
    <button class="btn">Search</button>
    <span class="muted">{{ $items->total() }} total</span>
</form>
<div class="table-wrap"><table class="table">
    <thead><tr><th>Email</th><th>Subscribed</th><th class="right">Actions</th></tr></thead>
    <tbody>
    @forelse ($items as $sub)
        <tr><td>{{ $sub->email }}</td><td>{{ $sub->created_at->format('j M Y') }}</td>
            <td class="right"><form method="post" action="{{ route('admin.subscribers.destroy', $sub) }}" class="inline" data-confirm="Remove this subscriber?">@csrf @method('delete')<button class="link link-danger">Remove</button></form></td></tr>
    @empty
        <tr><td colspan="3" class="empty-row">No subscribers yet. People sign up from the footer of every page.</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $items->links('admin.partials.pagination') }}
@endsection
