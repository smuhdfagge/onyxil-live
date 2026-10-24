<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Faq;
use App\Models\JobOpening;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\Markdown;
use App\Support\Seo;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function home()
    {
        $seo = Seo::make(
            Setting::get('site_name', 'Onyx Innovations Ltd').' — Software, cloud and data systems in Nigeria',
            Setting::get('seo_description'),
            'home'
        )->schema(Seo::organization())->schema([
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => Setting::get('site_name', 'Onyx Innovations Ltd'),
            'publisher' => ['@id' => url('/').'#organization'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('insights.index').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ]);

        return view('site.home', [
            'seo' => $seo,
            'services' => Service::published()->where('is_featured', true)->ordered()->get(),
            'products' => Product::published()->where('is_featured', true)->ordered()->get(),
            'projects' => Project::published()->where('is_featured', true)->ordered()->take(4)->get(),
            'posts' => Post::live()->latest('published_at')->take(3)->get(),
            'testimonials' => Testimonial::published()->ordered()->get(),
            'clients' => Client::published()->ordered()->get(),
            'faqs' => Faq::published()->where('group', 'General')->ordered()->take(6)->get(),
            'stats' => $this->pairs(Setting::get('stats')),
        ]);
    }

    public function about()
    {
        $seo = Seo::make('About us', Setting::get('about_intro'), 'about')
            ->crumbs(['About' => route('about')])
            ->schema(Seo::organization() + ['@type' => 'Organization']);

        return view('site.about', [
            'seo' => $seo,
            'team' => TeamMember::published()->ordered()->get(),
            'values' => $this->pairs(Setting::get('values')),
            'story' => Markdown::render(Setting::get('about_story')),
            'clients' => Client::published()->ordered()->get(),
        ]);
    }

    public function services()
    {
        $services = Service::published()->ordered()->get();
        $seo = Seo::make('IT services', 'Custom software, web and mobile apps, cloud hosting, data systems, networks and IT advisory for businesses, NGOs and government in Nigeria.', 'services')
            ->crumbs(['Services' => route('services.index')])
            ->schema([
                '@type' => 'ItemList',
                'itemListElement' => $services->values()->map(fn ($s, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'url' => route('services.show', $s), 'name' => $s->title])->all(),
            ]);

        return view('site.services', compact('seo', 'services') + ['faqs' => Faq::published()->where('group', 'General')->ordered()->get()]);
    }

    public function service(Service $service)
    {
        abort_unless($service->is_published, 404);
        $seo = Seo::make($service->meta_title ?: $service->title, $service->meta_description ?: $service->summary)
            ->image($service->image)
            ->crumbs(['Services' => route('services.index'), $service->title => route('services.show', $service)])
            ->schema([
                '@type' => 'Service',
                'name' => $service->title,
                'description' => $service->summary,
                'provider' => ['@id' => url('/').'#organization'],
                'areaServed' => ['@type' => 'Country', 'name' => 'Nigeria'],
                'url' => route('services.show', $service),
            ]);

        return view('site.service', [
            'seo' => $seo,
            'service' => $service,
            'body' => Markdown::render($service->body),
            'others' => Service::published()->whereKeyNot($service->id)->ordered()->get(),
            'projects' => Project::published()->ordered()->take(2)->get(),
        ]);
    }

    public function products()
    {
        $products = Product::published()->ordered()->get();
        $seo = Seo::make('Products', 'Software products built and run by Onyx Innovations, including MyBooks, cloud accounting for Nigerian businesses.', 'products')
            ->crumbs(['Products' => route('products.index')]);

        return view('site.products', compact('seo', 'products'));
    }

    public function product(Product $product)
    {
        abort_unless($product->is_published, 404);
        $seo = Seo::make($product->meta_title ?: $product->title.' — '.$product->tagline, $product->meta_description ?: $product->summary)
            ->image($product->image ?: $product->logo)
            ->crumbs(['Products' => route('products.index'), $product->title => route('products.show', $product)])
            ->schema(array_filter([
                '@type' => 'SoftwareApplication',
                'name' => $product->title,
                'applicationCategory' => 'BusinessApplication',
                'operatingSystem' => 'Web browser',
                'description' => $product->summary,
                'url' => $product->url ?: route('products.show', $product),
                'publisher' => ['@id' => url('/').'#organization'],
            ]));

        return view('site.product', [
            'seo' => $seo,
            'product' => $product,
            'body' => Markdown::render($product->body),
            'features' => $this->pairs($product->features),
            'faqs' => Faq::published()->where('group', $product->title)->ordered()->get(),
        ]);
    }

    public function work()
    {
        $projects = Project::published()->ordered()->get();
        $seo = Seo::make('Our work', 'Websites, SaaS products and business systems we have designed, built and launched.', 'work')
            ->crumbs(['Work' => route('work.index')]);

        return view('site.work', compact('seo', 'projects') + ['industries' => $projects->pluck('industry')->filter()->unique()->values()]);
    }

    public function project(Project $project)
    {
        abort_unless($project->is_published, 404);
        $seo = Seo::make($project->meta_title ?: $project->title, $project->meta_description ?: $project->summary)
            ->image($project->image)->type('article')
            ->crumbs(['Work' => route('work.index'), $project->title => route('work.show', $project)])
            ->schema(array_filter([
                '@type' => 'CreativeWork',
                'name' => $project->title,
                'description' => $project->summary,
                'creator' => ['@id' => url('/').'#organization'],
                'dateCreated' => $project->year,
                'url' => route('work.show', $project),
            ]));

        $all = Project::published()->ordered()->get();
        $i = $all->search(fn ($p) => $p->id === $project->id);

        return view('site.project', [
            'seo' => $seo,
            'project' => $project,
            'challenge' => Markdown::render($project->challenge),
            'solution' => Markdown::render($project->solution),
            'results' => Markdown::render($project->results),
            'next' => $all[$i + 1] ?? $all->first(),
        ]);
    }

    public function insights()
    {
        $q = trim((string) request('q'));
        $category = request('category');
        $posts = Post::live()
            ->when($q, fn ($query) => $query->where(fn ($w) => $w->where('title', 'like', "%$q%")->orWhere('excerpt', 'like', "%$q%")->orWhere('body', 'like', "%$q%")))
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest('published_at')->paginate(9)->withQueryString();

        $seo = Seo::make($category ? $category.' articles' : 'Insights', 'Practical writing on software, cloud, data and running a business with technology in Nigeria.', $category || $q ? null : 'insights')
            ->crumbs(['Insights' => route('insights.index')]);
        if ($q || $posts->currentPage() > 1) {
            $seo->noindex();
        }

        return view('site.insights', [
            'seo' => $seo,
            'posts' => $posts,
            'categories' => Post::live()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
            'q' => $q,
            'category' => $category,
        ]);
    }

    public function post(Post $post)
    {
        abort_unless($post->is_published && (! $post->published_at || $post->published_at->isPast()), 404);
        $post->increment('views');

        $seo = Seo::make($post->meta_title ?: $post->title, $post->meta_description ?: $post->excerpt)
            ->image($post->image)->type('article')
            ->crumbs(['Insights' => route('insights.index'), $post->title => route('insights.show', $post)])
            ->schema(array_filter([
                '@type' => 'BlogPosting',
                'headline' => Str::limit($post->title, 110, ''),
                'description' => $post->excerpt,
                'datePublished' => optional($post->published_at ?? $post->created_at)->toAtomString(),
                'dateModified' => $post->updated_at?->toAtomString(),
                'author' => ['@type' => $post->author ? 'Person' : 'Organization', 'name' => $post->author ?: Setting::get('site_name', 'Onyx Innovations Ltd')],
                'publisher' => ['@id' => url('/').'#organization'],
                'mainEntityOfPage' => route('insights.show', $post),
                'image' => $post->image ? asset($post->image) : null,
            ]));
        $seo->published = optional($post->published_at)->toAtomString();
        $seo->modified = $post->updated_at?->toAtomString();

        return view('site.post', [
            'seo' => $seo,
            'post' => $post,
            'body' => Markdown::renderWithAnchors($post->body),
            'toc' => Markdown::headings($post->body),
            'related' => Post::live()->whereKeyNot($post->id)->when($post->category, fn ($q) => $q->orderByRaw('category = ? desc', [$post->category]))->latest('published_at')->take(3)->get(),
        ]);
    }

    public function careers()
    {
        $seo = Seo::make('Careers', 'Open roles at Onyx Innovations. Join a small team building software that Nigerian organisations depend on.', 'careers')
            ->crumbs(['Careers' => route('careers.index')]);

        return view('site.careers', ['seo' => $seo, 'jobs' => JobOpening::open()->ordered()->get()]);
    }

    public function job(JobOpening $job)
    {
        abort_unless($job->is_published, 404);
        $seo = Seo::make($job->title.' — Careers', $job->summary)
            ->crumbs(['Careers' => route('careers.index'), $job->title => route('careers.show', $job)])
            ->schema(array_filter([
                '@type' => 'JobPosting',
                'title' => $job->title,
                'description' => Markdown::render($job->body) ?: $job->summary,
                'datePosted' => $job->created_at?->toDateString(),
                'validThrough' => $job->closes_at?->toDateString(),
                'employmentType' => Str::upper(str_replace(['-', ' '], '_', $job->type)),
                'hiringOrganization' => ['@type' => 'Organization', 'name' => Setting::get('site_name', 'Onyx Innovations Ltd'), 'sameAs' => url('/')],
                'jobLocation' => ['@type' => 'Place', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => $job->location, 'addressCountry' => 'NG']],
            ]));

        return view('site.job', ['seo' => $seo, 'job' => $job, 'body' => Markdown::render($job->body)]);
    }

    public function legal(string $page)
    {
        $map = ['privacy' => ['Privacy policy', 'privacy_policy'], 'terms' => ['Terms of use', 'terms']];
        abort_unless(isset($map[$page]), 404);
        [$title, $key] = $map[$page];

        return view('site.legal', [
            'seo' => Seo::make($title, $title.' for '.Setting::get('site_name', 'Onyx Innovations Ltd').'.')->crumbs([$title => url($page)]),
            'title' => $title,
            'body' => Markdown::render(Setting::get($key)),
        ]);
    }

    /** Turns "a | b" lines into [['a', 'b'], ...]. */
    public static function pairs($lines): array
    {
        if (is_string($lines)) {
            $lines = preg_split('/\r\n|\r|\n/', $lines);
        }

        return collect($lines ?: [])->map(fn ($l) => trim($l))->filter()->map(function ($l) {
            $parts = array_map('trim', explode('|', $l, 2));

            return [$parts[0], $parts[1] ?? null];
        })->values()->all();
    }
}
