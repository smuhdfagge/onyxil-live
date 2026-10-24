<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public const BUDGETS = ['Under ₦1m', '₦1m – ₦5m', '₦5m – ₦20m', 'Above ₦20m', 'Not sure yet'];
    public const TIMELINES = ['As soon as possible', 'Within 3 months', '3 – 6 months', 'Just exploring'];

    public function contact()
    {
        $seo = Seo::make('Contact us', 'Talk to Onyx Innovations about a software, cloud or data project. We reply within one working day.', 'contact')
            ->crumbs(['Contact' => route('contact')])
            ->schema(['@type' => 'ContactPage', 'url' => route('contact'), 'about' => ['@id' => url('/').'#organization']]);

        return view('site.contact', compact('seo'));
    }

    public function sendContact(Request $request)
    {
        if ($this->isBot($request)) {
            return redirect()->route('contact')->with('sent', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:160'],
            'subject' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $msg = Message::create($data + ['ip' => $request->ip()]);
        $this->notify('New message from '.$msg->name, $data);

        return redirect()->route('contact')->with('sent', true);
    }

    public function quote()
    {
        $seo = Seo::make('Start a project', 'Tell us what you want to build. We will come back with questions, a rough plan and a cost range within two working days.', 'quote')
            ->crumbs(['Start a project' => route('quote')]);

        return view('site.quote', [
            'seo' => $seo,
            'services' => Service::published()->ordered()->pluck('title'),
            'budgets' => self::BUDGETS,
            'timelines' => self::TIMELINES,
        ]);
    }

    public function sendQuote(Request $request)
    {
        if ($this->isBot($request)) {
            return redirect()->route('quote')->with('sent', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:160'],
            'service' => ['nullable', 'string', 'max:190'],
            'budget' => ['nullable', 'in:'.implode(',', self::BUDGETS)],
            'timeline' => ['nullable', 'in:'.implode(',', self::TIMELINES)],
            'details' => ['required', 'string', 'min:20', 'max:8000'],
        ]);

        $quote = QuoteRequest::create($data + ['ip' => $request->ip(), 'status' => 'new']);
        $this->notify('New project enquiry from '.$quote->name, $data);

        return redirect()->route('quote')->with('sent', true);
    }

    public function subscribe(Request $request)
    {
        if (! $this->isBot($request, false)) {
            $data = $request->validate(['email' => ['required', 'email', 'max:190']]);
            Subscriber::firstOrCreate(['email' => strtolower($data['email'])], ['ip' => $request->ip()]);
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'You are on the list. Thank you.']);
        }

        return back()->with('subscribed', true);
    }

    /** Honeypot plus a minimum fill time keeps most form spam out without a captcha. */
    protected function isBot(Request $request, bool $checkTime = true): bool
    {
        if (filled($request->input('website_url'))) {
            return true;
        }
        if ($checkTime) {
            $started = (int) $request->input('_started', 0);
            if ($started && (time() - $started) < 3) {
                return true;
            }
        }

        return false;
    }

    protected function notify(string $subject, array $data): void
    {
        $to = Setting::get('notify_email');
        if (! $to) {
            return;
        }
        try {
            $body = collect($data)->filter()->map(fn ($v, $k) => ucfirst($k).': '.$v)->implode("\n\n");
            Mail::raw($body."\n\n— Sent from ".url('/'), function ($m) use ($to, $subject, $data) {
                $m->to($to)->subject($subject)->replyTo($data['email'], $data['name']);
            });
        } catch (\Throwable $e) {
            Log::warning('Enquiry email failed: '.$e->getMessage());
        }
    }
}
