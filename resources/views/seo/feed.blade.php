{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>{{ $name }} — Insights</title>
    <link>{{ route('insights.index') }}</link>
    <atom:link href="{{ route('feed') }}" rel="self" type="application/rss+xml"/>
    <description>Practical writing on software, data and technology in Nigeria.</description>
    <language>en-ng</language>
@foreach ($posts as $p)
    <item>
        <title>{{ $p->title }}</title>
        <link>{{ route('insights.show', $p) }}</link>
        <guid isPermaLink="true">{{ route('insights.show', $p) }}</guid>
        <pubDate>{{ optional($p->published_at ?? $p->created_at)->toRssString() }}</pubDate>
        @if ($p->category)<category>{{ $p->category }}</category>@endif
        <description>{{ $p->excerpt }}</description>
    </item>
@endforeach
</channel>
</rss>
