@extends('layouts.site')
@section('content')
<section class="hero" style="min-height:80svh">
    <canvas class="hero-stone" data-stone aria-hidden="true"></canvas>
    <div class="hero-shade" aria-hidden="true"></div>
    <div class="wrap hero-inner">
        <p class="hero-kicker">Error {{ $code }}</p>
        <h1 class="hero-title" style="font-size:clamp(3rem,8vw,6.5rem);max-width:14ch">@yield('title')</h1>
        <p class="hero-text">@yield('text')</p>
        <div class="hero-actions">
            <a href="{{ url('/') }}" class="btn btn-gold btn-lg"><span>Go to the home page</span></a>
            <a href="{{ url('/contact') }}" class="btn btn-line btn-lg"><span>Contact us</span></a>
        </div>
    </div>
</section>
@endsection
