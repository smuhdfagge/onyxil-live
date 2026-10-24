<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Subscriber;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $days = collect(range(13, 0))->map(fn ($d) => Carbon::today()->subDays($d));
        $since = $days->first();
        $msg = Message::where('created_at', '>=', $since)->get(['created_at'])->groupBy(fn ($m) => $m->created_at->toDateString());
        $quo = QuoteRequest::where('created_at', '>=', $since)->get(['created_at'])->groupBy(fn ($m) => $m->created_at->toDateString());

        $chart = $days->map(fn ($d) => [
            'label' => $d->format('j M'),
            'messages' => count($msg[$d->toDateString()] ?? []),
            'quotes' => count($quo[$d->toDateString()] ?? []),
        ]);

        return view('admin.dashboard', [
            'counts' => [
                'unread' => Message::where('is_read', false)->count(),
                'newQuotes' => QuoteRequest::where('status', 'new')->count(),
                'subscribers' => Subscriber::count(),
                'posts' => Post::count(),
                'services' => Service::count(),
                'projects' => Project::count(),
                'products' => Product::count(),
            ],
            'chart' => $chart,
            'latestMessages' => Message::latest()->take(5)->get(),
            'latestQuotes' => QuoteRequest::latest()->take(5)->get(),
            'topPosts' => Post::orderByDesc('views')->take(5)->get(['id', 'title', 'views', 'slug']),
        ]);
    }
}
