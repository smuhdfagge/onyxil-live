<section class="cta-band">
    <div class="wrap cta-inner">
        <div>
            <h2 class="cta-title">{{ $s['cta_title'] ?? 'Have something that needs building?' }}</h2>
            <p>{{ $s['cta_text'] ?? '' }}</p>
        </div>
        <div class="cta-actions">
            <a href="{{ route('quote') }}" class="btn btn-ink btn-lg" data-magnetic><span>Start a project</span>@include('partials.icon', ['name' => 'arrow', 'size' => 18])</a>
            @if (! empty($s['whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $s['whatsapp']) }}" class="btn btn-line-ink btn-lg" target="_blank" rel="noopener"><span>Chat on WhatsApp</span></a>
            @else
                <a href="{{ route('contact') }}" class="btn btn-line-ink btn-lg"><span>Contact us</span></a>
            @endif
        </div>
    </div>
</section>
