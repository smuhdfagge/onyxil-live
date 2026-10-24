@extends('layouts.admin')
@section('title', 'Project enquiries')
@section('content')
<form class="toolbar" method="get">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search name, email or organisation">
    <select name="status" onchange="this.form.submit()">
        <option value="">Every status</option>
        @foreach ($statuses as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
    </select>
    <button class="btn">Search</button>
</form>
<div class="table-wrap"><table class="table">
    <thead><tr><th>From</th><th>Service</th><th>Budget</th><th>Status</th><th>Received</th></tr></thead>
    <tbody>
    @forelse ($items as $q)
        <tr @class(['row-unread' => $q->status === 'new'])>
            <td><a href="{{ route('admin.quotes.show', $q) }}" class="strong">{{ $q->name }}</a><br><small class="muted">{{ $q->company ?: $q->email }}</small></td>
            <td>{{ $q->service ?: '—' }}</td>
            <td>{{ $q->budget ?: '—' }}</td>
            <td><span class="pill pill-{{ $q->status }}">{{ $statuses[$q->status] ?? $q->status }}</span></td>
            <td class="nowrap">{{ $q->created_at->format('j M Y') }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="empty-row">No project enquiries yet. They arrive here from the Start a project page.</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $items->links('admin.partials.pagination') }}
@endsection
