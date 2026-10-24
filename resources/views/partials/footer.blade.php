@php
    $socials = array_filter([
        'linkedin' => $s['social_linkedin'] ?? null, 'x' => $s['social_x'] ?? null, 'facebook' => $s['social_facebook'] ?? null,
        'instagram' => $s['social_instagram'] ?? null, 'github' => $s['social_github'] ?? null, 'youtube' => $s['social_youtube'] ?? null,
    ]);
@endphp
<footer class="site-footer">
    <div class="footer-glow" aria-hidden="true"></div>
    <div class="wrap">
        <div class="footer-top">
            <div class="footer-brand">
                <img src="{{ asset('images/logo-light.png') }}" alt="Onyx Innovations Limited" width="180" height="80" loading="lazy">
                <p>{{ $s['site_tagline'] ?? '' }}</p>
                @if ($socials)
                    <ul class="socials" aria-label="Social media">
                        @foreach ($socials as $net => $url)
                            <li><a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($net) }}">@include('partials.icon', ['name' => $net, 'size' => 18])</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="footer-news">
                <h2 class="footer-h">Get our occasional notes</h2>
                <p>Short, useful writing on software, data and running a business with technology. No more than once a month.</p>
                <form class="subscribe" method="post" action="{{ route('subscribe') }}" data-subscribe>
                    @csrf
                    <label class="sr-only" for="sub-email">Email address</label>
                    <input id="sub-email" type="email" name="email" placeholder="you@company.com" required autocomplete="email">
                    <input type="text" name="website_url" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <button type="submit" class="btn btn-gold btn-sm"><span>Subscribe</span></button>
                    <p class="subscribe-msg" role="status" aria-live="polite">@if(session('subscribed')) You are on the list. Thank you. @endif</p>
                </form>
            </div>
        </div>

        <div class="footer-cols">
            <div>
                <h2 class="footer-h">Services</h2>
                <ul>
                    @foreach ($navServices as $sv)
                        <li><a href="{{ route('services.show', $sv) }}">{{ $sv->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h2 class="footer-h">Company</h2>
                <ul>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('work.index') }}">Our work</a></li>
                    <li><a href="{{ route('insights.index') }}">Insights</a></li>
                    <li><a href="{{ route('careers.index') }}">Careers</a></li>
                    <li><a href="{{ route('quote') }}">Start a project</a></li>
                </ul>
            </div>
            <div>
                <h2 class="footer-h">Products</h2>
                <ul>
                    @foreach ($navProducts as $p)
                        <li><a href="{{ route('products.show', $p) }}">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h2 class="footer-h">Talk to us</h2>
                <ul class="footer-contact">
                    @if (! empty($s['email']))<li>@include('partials.icon', ['name' => 'mail', 'size' => 16])<a href="mailto:{{ $s['email'] }}">{{ $s['email'] }}</a></li>@endif
                    @if (! empty($s['phone']))<li>@include('partials.icon', ['name' => 'phone', 'size' => 16])<a href="tel:{{ preg_replace('/\s+/', '', $s['phone']) }}">{{ $s['phone'] }}</a></li>@endif
                    @if (! empty($s['address']))<li>@include('partials.icon', ['name' => 'pin', 'size' => 16])<span>{!! nl2br(e($s['address'])) !!}</span></li>@endif
                    @if (! empty($s['hours']))<li>@include('partials.icon', ['name' => 'clock', 'size' => 16])<span>{{ $s['hours'] }}</span></li>@endif
                </ul>
            </div>
        </div>

        <div class="footer-word" aria-hidden="true">ONYX</div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} Onyx Innovations Limited. @if(! empty($s['rc_number'])) RC {{ $s['rc_number'] }}. @endif All rights reserved.</p>
            <ul>
                <li><a href="{{ url('privacy') }}">Privacy</a></li>
                <li><a href="{{ url('terms') }}">Terms</a></li>
                <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
            </ul>
        </div>
    </div>
</footer>
