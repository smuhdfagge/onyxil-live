<?php

namespace App\Http\Controllers;

use App\Models\JobOpening;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $xml = Cache::remember('sitemap_xml', 3600, function () {
            $urls = [];
            $add = function ($loc, $mod = null, $freq = 'monthly', $prio = '0.6') use (&$urls) {
                $urls[] = compact('loc', 'mod', 'freq', 'prio');
            };
            $add(route('home'), now(), 'weekly', '1.0');
            foreach (['about', 'services.index', 'products.index', 'work.index', 'insights.index', 'careers.index', 'contact', 'quote'] as $r) {
                $add(route($r), null, 'weekly', '0.8');
            }
            foreach (Service::published()->get() as $m) {
                $add(route('services.show', $m), $m->updated_at, 'monthly', '0.8');
            }
            foreach (Product::published()->get() as $m) {
                $add(route('products.show', $m), $m->updated_at, 'monthly', '0.9');
            }
            foreach (Project::published()->get() as $m) {
                $add(route('work.show', $m), $m->updated_at, 'yearly', '0.6');
            }
            foreach (Post::live()->get() as $m) {
                $add(route('insights.show', $m), $m->updated_at, 'monthly', '0.7');
            }
            foreach (JobOpening::open()->get() as $m) {
                $add(route('careers.show', $m), $m->updated_at, 'weekly', '0.5');
            }
            $add(url('privacy'), null, 'yearly', '0.2');
            $add(url('terms'), null, 'yearly', '0.2');

            return view('seo.sitemap', compact('urls'))->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /*?q=',
            'Allow: /',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain']);
    }

    public function feed()
    {
        $posts = Post::live()->latest('published_at')->take(20)->get();

        return response()->view('seo.feed', ['posts' => $posts, 'name' => Setting::get('site_name', 'Onyx Innovations Ltd')], 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }

    public function manifest()
    {
        return response()->json([
            'name' => Setting::get('site_name', 'Onyx Innovations Ltd'),
            'short_name' => 'Onyx',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#0d0b08',
            'theme_color' => '#0d0b08',
            'icons' => [
                ['src' => asset('images/icon-192.png'), 'sizes' => '192x192', 'type' => 'image/png'],
                ['src' => asset('images/icon-512.png'), 'sizes' => '512x512', 'type' => 'image/png'],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json']);
    }
}
