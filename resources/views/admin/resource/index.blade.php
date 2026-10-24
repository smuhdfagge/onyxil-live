@extends('layouts.admin')
@section('title', $r['label'])
@section('actions')
    @unless ($r['no_create'] ?? false)
        <a href="{{ route('admin.resource.create', $r['key']) }}" class="btn btn-primary">Add {{ $r['singular'] }}</a>
    @endunless
@endsection
@section('content')
<form class="toolbar" method="get">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search {{ strtolower($r['label']) }}">
    <button class="btn">Search</button>
    @if (request('q'))<a href="{{ route('admin.resource.index', $r['key']) }}" class="btn btn-ghost">Clear</a>@endif
    <span class="muted">{{ $items->total() }} {{ \Illuminate\Support\Str::plural($r['singular'], $items->total()) }}</span>
</form>

<div class="table-wrap">
<table class="table">
    <thead><tr>
        @foreach ($r['columns'] as $col => $label)<th>{{ $label }}</th>@endforeach
        <th class="right">Actions</th>
    </tr></thead>
    <tbody>
    @forelse ($items as $item)
        <tr>
            @foreach ($r['columns'] as $col => $label)
                <td>
                    @php $v = $item->{$col}; @endphp
                    @if (is_bool($v) || in_array($col, ['is_published', 'is_featured']))
                        @if ($col === 'is_published')
                            <form method="post" action="{{ route('admin.resource.toggle', [$r['key'], $item->id]) }}">@csrf
                                <button class="pill {{ $v ? 'pill-on' : 'pill-off' }}" title="Click to {{ $v ? 'hide' : 'publish' }}">{{ $v ? 'Published' : 'Hidden' }}</button>
                            </form>
                        @else
                            <span class="pill {{ $v ? 'pill-on' : 'pill-off' }}">{{ $v ? 'Yes' : 'No' }}</span>
                        @endif
                    @elseif ($v instanceof \DateTimeInterface)
                        {{ $v->format('j M Y') }}
                    @elseif ($loop->first)
                        <a href="{{ route('admin.resource.edit', [$r['key'], $item->id]) }}" class="strong">{{ \Illuminate\Support\Str::limit($v, 70) }}</a>
                    @else
                        {{ \Illuminate\Support\Str::limit(is_array($v) ? implode(', ', $v) : $v, 60) }}
                    @endif
                </td>
            @endforeach
            <td class="right nowrap">
                @if (! empty($r['public']) && ($item->is_published ?? true))
                    <a href="{{ route($r['public'], $item) }}" target="_blank" rel="noopener" class="link">View</a>
                @endif
                <a href="{{ route('admin.resource.edit', [$r['key'], $item->id]) }}" class="link">Edit</a>
                @unless ($r['no_delete'] ?? false)
                    <form method="post" action="{{ route('admin.resource.destroy', [$r['key'], $item->id]) }}" class="inline" data-confirm="Delete this {{ $r['singular'] }}? This cannot be undone.">
                        @csrf @method('delete')<button class="link link-danger">Delete</button>
                    </form>
                @endunless
            </td>
        </tr>
    @empty
        <tr><td colspan="{{ count($r['columns']) + 1 }}" class="empty-row">
            @if (request('q')) Nothing matches “{{ request('q') }}”. @else No {{ strtolower($r['label']) }} yet. @endif
            @unless ($r['no_create'] ?? false) <a href="{{ route('admin.resource.create', $r['key']) }}">Add the first one</a>. @endunless
        </td></tr>
    @endforelse
    </tbody>
</table>
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
