@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<nav class="tabs">
    @foreach ($groups as $g => $fields)<a href="#{{ \Illuminate\Support\Str::slug($g) }}">{{ $g }}</a>@endforeach
</nav>
<form method="post" action="{{ route('admin.settings.update') }}" class="edit-form" data-dirty-guard>
    @csrf @method('put')
    <div class="edit-main">
        @foreach ($groups as $g => $fields)
            <section class="panel" id="{{ \Illuminate\Support\Str::slug($g) }}">
                <h2>{{ $g }}</h2>
                <div class="fields">
                    @foreach ($fields as $key => $f)
                        @include('admin.partials.field', ['name' => $key, 'f' => $f, 'value' => $values[$key] ?? null])
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
    <div class="save-bar"><button class="btn btn-primary">Save settings</button></div>
</form>
@endsection
