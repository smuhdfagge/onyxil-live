{{-- FAQ accordion plus FAQPage structured data. Expects $faqs. --}}
<div class="faq">
    @foreach ($faqs as $faq)
        <details class="faq-item" @if($loop->first) open @endif>
            <summary><span>{{ $faq->question }}</span><i class="faq-sign" aria-hidden="true"></i></summary>
            <div class="faq-a"><p>{{ $faq->answer }}</p></div>
        </details>
    @endforeach
</div>
@push('head')
<script type="application/ld+json">{!! \App\Support\Seo::ldJson([
    '@type' => 'FAQPage',
    'mainEntity' => $faqs->map(fn ($f) => ['@type' => 'Question', 'name' => $f->question, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer]])->values()->all(),
]) !!}</script>
@endpush
