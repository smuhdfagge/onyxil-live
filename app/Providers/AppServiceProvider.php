<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\Product;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('partials.pagination');
        Schema::defaultStringLength(191); // keeps older MySQL and MariaDB happy with unique indexes

        if (str_starts_with((string) config('app.url'), 'https://') && $this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer(['layouts.site', 'site.*', 'partials.*'], function ($view) {
            static $shared = null;
            $shared ??= [
                's' => Setting::all_cached(),
                'navServices' => rescue(fn () => Service::published()->ordered()->get(['id', 'title', 'slug', 'icon']), collect(), false),
                'navProducts' => rescue(fn () => Product::published()->ordered()->get(['id', 'title', 'slug', 'tagline']), collect(), false),
            ];
            $view->with($shared);
        });

        View::composer('layouts.admin', function ($view) {
            $view->with([
                'unreadCount' => Message::where('is_read', false)->count(),
                'newQuoteCount' => QuoteRequest::where('status', 'new')->count(),
            ]);
        });
    }
}
