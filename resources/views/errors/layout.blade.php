@extends('layouts.site')
@section('content')
<section class="page-hero error-hero">
    <div class="wrap">
        <p class="eyebrow-pill">Error {{ $code }}</p>
        <h1 class="page-title">@yield('title')</h1>
        <p class="page-lead">@yield('text')</p>
        <div class="btn-row">
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Go to the home page</a>
            <a href="{{ url('/contact') }}" class="btn btn-outline btn-lg">Contact us</a>
        </div>
    </div>
</section>
@endsection
