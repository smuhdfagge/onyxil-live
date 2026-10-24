@props(['seed' => 'onyx', 'label' => null])
{{-- Generated facet artwork used when a project or article has no uploaded image. --}}
@php
    $h = crc32($seed);
    $rand = function ($min, $max) use (&$h) { $h = ($h * 1103515245 + 12345) & 0x7fffffff; return $min + ($h % max(1, $max - $min)); };
    $pts = [];
    for ($i = 0; $i < 9; $i++) { $pts[] = [$rand(0, 800), $rand(0, 500)]; }
    $id = 'g'.substr(md5($seed), 0, 6);
    $angle = $rand(10, 80);
@endphp
<svg {{ $attributes->merge(['class' => 'cover-art']) }} viewBox="0 0 800 500" preserveAspectRatio="xMidYMid slice" role="img" aria-label="{{ $label ?? 'Abstract artwork' }}">
    <defs>
        <linearGradient id="{{ $id }}a" gradientTransform="rotate({{ $angle }})">
            <stop offset="0" stop-color="#0e0b07"/><stop offset=".55" stop-color="#3a2a0c"/><stop offset="1" stop-color="#c8951f"/>
        </linearGradient>
        <linearGradient id="{{ $id }}b" x1="0" y1="1" x2="1" y2="0">
            <stop offset="0" stop-color="#e2b44e" stop-opacity=".0"/><stop offset="1" stop-color="#e2b44e" stop-opacity=".55"/>
        </linearGradient>
    </defs>
    <rect width="800" height="500" fill="#0e0b07"/>
    <polygon points="{{ $pts[0][0] }},0 800,0 800,{{ $pts[1][1] }} {{ $pts[2][0] }},500 0,500 0,{{ $pts[3][1] }}" fill="url(#{{ $id }}a)" opacity=".9"/>
    <polygon points="{{ $pts[4][0] }},{{ $pts[4][1] }} {{ $pts[5][0] }},{{ $pts[5][1] }} {{ $pts[6][0] }},{{ $pts[6][1] }}" fill="url(#{{ $id }}b)"/>
    <polygon points="{{ $pts[6][0] }},{{ $pts[6][1] }} {{ $pts[7][0] }},{{ $pts[7][1] }} 800,500" fill="#e2b44e" opacity=".14"/>
    <g stroke="#e2b44e" stroke-opacity=".35" fill="none">
        @foreach ($pts as $i => $p)
            @if ($i > 0)<line x1="{{ $pts[$i-1][0] }}" y1="{{ $pts[$i-1][1] }}" x2="{{ $p[0] }}" y2="{{ $p[1] }}"/>@endif
        @endforeach
    </g>
    <g fill="#f3eee4">
        @foreach ($pts as $p)<circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="2.5"/>@endforeach
    </g>
</svg>
