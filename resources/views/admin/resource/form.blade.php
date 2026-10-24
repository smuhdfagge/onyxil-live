@extends('layouts.admin')
@php
    $editing = $item->exists;
    $groups = collect($r['fields'])->groupBy(fn ($f) => $f['group'] ?? 'Details', true);
@endphp
@section('title', $editing ? 'Edit '.$r['singular'] : 'Add '.$r['singular'])
@section('actions')
    <a href="{{ route('admin.resource.index', $r['key']) }}" class="btn btn-ghost">← All {{ strtolower($r['label']) }}</a>
    @if ($editing && ! empty($r['public']) && ($item->is_published ?? true))
        <a href="{{ route($r['public'], $item) }}" target="_blank" rel="noopener" class="btn">View on site ↗</a>
    @endif
@endsection
@section('content')
<form method="post" enctype="multipart/form-data" action="{{ $editing ? route('admin.resource.update', [$r['key'], $item->id]) : route('admin.resource.store', $r['key']) }}" class="edit-form" data-dirty-guard>
    @csrf
    @if ($editing) @method('put') @endif

    <div class="edit-main">
        @foreach ($groups as $group => $fields)
            <section class="panel">
                <h2>{{ $group === 'SEO' ? 'Search engines' : $group }}</h2>
                @if ($group === 'SEO')
                    <div class="serp" data-serp aria-hidden="true">
                        <span class="serp-url">{{ parse_url(config('app.url'), PHP_URL_HOST) }} › {{ $r['key'] }}</span>
                        <span class="serp-title" data-serp-title>{{ $item->meta_title ?: ($item->title ?? '') }}</span>
                        <span class="serp-desc" data-serp-desc>{{ $item->meta_description ?: ($item->summary ?? $item->excerpt ?? '') }}</span>
                    </div>
                    <p class="help">This is roughly how the page will look in Google. If the boxes are empty, the title and summary are used.</p>
                @endif
                <div class="fields">
                    @foreach ($fields as $name => $f)
                        @include('admin.partials.field', ['name' => $name, 'f' => $f, 'value' => $item->{$name}])
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <div class="save-bar">
        <button type="submit" class="btn btn-primary">{{ $editing ? 'Save changes' : 'Create '.$r['singular'] }}</button>
        @if ($editing)<span class="muted">Last saved {{ $item->updated_at?->diffForHumans() }}</span>@endif
    </div>
</form>

@if ($editing && ! ($r['no_delete'] ?? false))
    <form method="post" action="{{ route('admin.resource.destroy', [$r['key'], $item->id]) }}" class="danger-zone" data-confirm="Delete this {{ $r['singular'] }}? This cannot be undone.">
        @csrf @method('delete')
        <button class="btn btn-danger">Delete this {{ $r['singular'] }}</button>
    </form>
@endif
@endsection
