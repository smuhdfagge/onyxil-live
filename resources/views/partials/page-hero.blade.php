{{-- Inner page header. Expects $title, optional $lead, $crumbs (label => url). --}}
<section class="page-hero">
    <div class="page-hero-art" aria-hidden="true"><canvas data-lattice data-density="0.6"></canvas></div>
    <div class="wrap">
        @if (! empty($crumbs))
            <nav class="crumbs" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    @foreach ($crumbs as $label => $url)
                        @if ($loop->last)
                            <li aria-current="page">{{ \Illuminate\Support\Str::limit($label, 48) }}</li>
                        @else
                            <li><a href="{{ $url }}">{{ $label }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif
        <h1 class="page-title">{{ $title }}</h1>
        @if (! empty($lead))<p class="page-lead">{{ $lead }}</p>@endif
        {{ $slot ?? '' }}
    </div>
</section>
