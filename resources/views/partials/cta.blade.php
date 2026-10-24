<section class="cta-band">
    <div class="wrap cta-inner">
        <div>
            <h2 class="cta-title">{{ $s['cta_title'] ?? 'Have something that needs building?' }}</h2>
            <p>{{ $s['cta_text'] ?? '' }}</p>
        </div>
        <div class="btn-row">
            <a href="{{ route('quote') }}" class="btn btn-accent btn-lg">Start a project @include('partials.icon', ['name' => 'arrow', 'size' => 18])</a>
            @if (! empty($s['whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $s['whatsapp']) }}" class="btn btn-outline btn-lg" target="_blank" rel="noopener">Chat on WhatsApp</a>
            @else
                <a href="{{ route('contact') }}" class="btn btn-outline btn-lg">Contact us</a>
            @endif
        </div>
    </div>
</section>
