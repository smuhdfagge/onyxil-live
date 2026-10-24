<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\QuoteRequest;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public const QUOTE_STATUSES = ['new' => 'New', 'contacted' => 'Contacted', 'proposal' => 'Proposal sent', 'won' => 'Won', 'lost' => 'Closed, not won'];

    public function messages(Request $request)
    {
        $items = Message::query()
            ->when($request->query('q'), fn ($q, $t) => $q->where(fn ($w) => $w->where('name', 'like', "%$t%")->orWhere('email', 'like', "%$t%")->orWhere('subject', 'like', "%$t%")))
            ->when($request->query('filter') === 'unread', fn ($q) => $q->where('is_read', false))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.inbox.messages', compact('items'));
    }

    public function message(Message $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.inbox.message', compact('message'));
    }

    public function markUnread(Message $message)
    {
        $message->update(['is_read' => false]);

        return redirect()->route('admin.messages')->with('status', 'Marked as unread.');
    }

    public function destroyMessage(Message $message)
    {
        $message->delete();

        return redirect()->route('admin.messages')->with('status', 'Message deleted.');
    }

    public function quotes(Request $request)
    {
        $items = QuoteRequest::query()
            ->when($request->query('q'), fn ($q, $t) => $q->where(fn ($w) => $w->where('name', 'like', "%$t%")->orWhere('email', 'like', "%$t%")->orWhere('company', 'like', "%$t%")))
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.inbox.quotes', ['items' => $items, 'statuses' => self::QUOTE_STATUSES]);
    }

    public function quote(QuoteRequest $quote)
    {
        return view('admin.inbox.quote', ['quote' => $quote, 'statuses' => self::QUOTE_STATUSES]);
    }

    public function updateQuote(Request $request, QuoteRequest $quote)
    {
        $quote->update($request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(self::QUOTE_STATUSES))],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]));

        return back()->with('status', 'Quote updated.');
    }

    public function destroyQuote(QuoteRequest $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes')->with('status', 'Quote request deleted.');
    }

    public function subscribers(Request $request)
    {
        $items = Subscriber::when($request->query('q'), fn ($q, $t) => $q->where('email', 'like', "%$t%"))->latest()->paginate(30)->withQueryString();

        return view('admin.inbox.subscribers', compact('items'));
    }

    public function exportSubscribers()
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['email', 'subscribed_at']);
            Subscriber::orderBy('id')->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [$r->email, $r->created_at]);
                }
            });
            fclose($out);
        }, 'onyx-subscribers-'.date('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroySubscriber(Subscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('status', 'Subscriber removed.');
    }
}
