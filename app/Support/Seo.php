<?php

namespace App\Support;

use App\Models\SeoPage;
use App\Models\Setting;
use Illuminate\Support\Str;

class Seo
{
    public string $title = '';
    public string $description = '';
    public ?string $image = null;
    public string $type = 'website';
    public ?string $canonical = null;
    public bool $index = true;
    public array $schema = [];
    public array $breadcrumbs = [];
    public ?string $published = null;
    public ?string $modified = null;

    public static function make(string $title, ?string $description = null, ?string $page = null): static
    {
        $seo = new static;
        $override = $page ? SeoPage::where('page', $page)->first() : null;
        $seo->title = $override?->meta_title ?: $title;
        $seo->description = Str::limit(strip_tags($override?->meta_description ?: $description ?: Setting::get('seo_description', '')), 300, '');
        if ($override?->og_image) {
            $seo->image = asset($override->og_image);
        }

        return $seo;
    }

    public function image(?string $url): static
    {
        if ($url) {
            $this->image = Str::startsWith($url, 'http') ? $url : asset($url);
        }

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function crumbs(array $crumbs): static
    {
        $this->breadcrumbs = $crumbs;

        return $this;
    }

    public function schema(array $schema): static
    {
        $this->schema[] = $schema;

        return $this;
    }

    public function noindex(): static
    {
        $this->index = false;

        return $this;
    }

    public function fullTitle(): string
    {
        $suffix = Setting::get('seo_title_suffix', '| '.Setting::get('site_name', 'Onyx Innovations Ltd'));

        return Str::contains($this->title, trim($suffix, '| ')) ? $this->title : trim($this->title.' '.$suffix);
    }

    public function jsonLd(): array
    {
        $graph = $this->schema;
        if ($this->breadcrumbs) {
            $items = [];
            $pos = 1;
            $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => 'Home', 'item' => route('home')];
            foreach ($this->breadcrumbs as $name => $url) {
                $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $name, 'item' => $url];
            }
            $graph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
        }

        return $graph;
    }

    /** JSON-LD script body. Built here because Blade would read "@context" as a directive. */
    public static function ldJson(array $data): string
    {
        return json_encode(['@context' => 'https://schema.org'] + $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }

    public static function organization(): array
    {
        $sameAs = array_values(array_filter([
            Setting::get('social_linkedin'), Setting::get('social_x'), Setting::get('social_facebook'),
            Setting::get('social_instagram'), Setting::get('social_github'), Setting::get('social_youtube'),
        ]));

        return array_filter([
            '@type' => 'Organization',
            '@id' => url('/').'#organization',
            'name' => Setting::get('site_name', 'Onyx Innovations Ltd'),
            'legalName' => 'Onyx Innovations Limited',
            'url' => url('/'),
            'logo' => asset('images/logo-dark.png'),
            'email' => Setting::get('email'),
            'telephone' => Setting::get('phone'),
            'description' => Setting::get('seo_description'),
            'address' => Setting::get('address') ? [
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('address'),
                'addressLocality' => 'Kano',
                'addressRegion' => 'Kano State',
                'addressCountry' => 'NG',
            ] : null,
            'areaServed' => 'NG',
            'sameAs' => $sameAs ?: null,
        ]);
    }
}
