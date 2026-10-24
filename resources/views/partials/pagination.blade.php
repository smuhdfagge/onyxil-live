@if ($paginator->hasPages())
<nav class="pagination" aria-label="Pages">
    @if ($paginator->onFirstPage())
        <span class="page disabled" aria-disabled="true">Previous</span>
    @else
        <a class="page" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
    @endif
    @foreach ($elements as $element)
        @if (is_string($element))<span class="page dots">{{ $element }}</span>@endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page current" aria-current="page">{{ $page }}</span>
                @else
                    <a class="page" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach
    @if ($paginator->hasMorePages())
        <a class="page" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
    @else
        <span class="page disabled" aria-disabled="true">Next</span>
    @endif
</nav>
@endif
