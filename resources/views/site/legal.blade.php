@extends('layouts.site')
@section('content')
@include('partials.page-hero', ['title' => $title, 'crumbs' => [$title => url()->current()]])
<section class="section"><div class="wrap"><div class="prose">{!! $body ?: '<p>This page is being updated.</p>' !!}</div></div></section>
@endsection
