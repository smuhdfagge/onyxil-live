<?php

namespace App\Admin;

use App\Models;

/**
 * Describes every editable content type in the admin panel.
 * One generic controller and one set of views read from here,
 * so adding a field is a one-line change.
 *
 * Field types: text, textarea, markdown, image, bool, number, date,
 * datetime, select, list (one item per line), url, email, icon.
 */
class Resources
{
    public const ICONS = [
        'code' => 'Code', 'cloud' => 'Cloud', 'chart' => 'Chart', 'shield' => 'Shield',
        'network' => 'Network', 'mobile' => 'Mobile', 'database' => 'Database', 'compass' => 'Compass',
        'cap' => 'Graduation cap', 'gear' => 'Gear', 'spark' => 'Spark', 'ledger' => 'Ledger',
    ];

    public static function all(): array
    {
        $seo = [
            'meta_title' => ['type' => 'text', 'label' => 'SEO title', 'help' => 'Shown in Google results and browser tabs. Around 55 characters.', 'group' => 'SEO'],
            'meta_description' => ['type' => 'textarea', 'label' => 'SEO description', 'help' => 'One or two sentences for search results. Around 155 characters.', 'max' => 300, 'group' => 'SEO'],
        ];

        return [
            'services' => [
                'model' => Models\Service::class,
                'label' => 'Services', 'singular' => 'service', 'title' => 'title',
                'columns' => ['title' => 'Title', 'is_featured' => 'On home page', 'is_published' => 'Published', 'sort' => 'Order'],
                'search' => ['title', 'summary'], 'order' => ['sort', 'asc'],
                'public' => 'services.show',
                'fields' => [
                    'title' => ['type' => 'text', 'required' => true],
                    'slug' => ['type' => 'text', 'help' => 'Leave empty to build it from the title.'],
                    'icon' => ['type' => 'icon'],
                    'summary' => ['type' => 'textarea', 'max' => 500, 'help' => 'One or two sentences used on cards and in listings.'],
                    'body' => ['type' => 'markdown', 'label' => 'Full description'],
                    'features' => ['type' => 'list', 'label' => 'What is included', 'help' => 'One item per line.'],
                    'deliverables' => ['type' => 'list', 'label' => 'What you get at the end', 'help' => 'One item per line.'],
                    'image' => ['type' => 'image'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_featured' => ['type' => 'bool', 'label' => 'Show on the home page'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ] + $seo,
            ],
            'products' => [
                'model' => Models\Product::class,
                'label' => 'Products', 'singular' => 'product', 'title' => 'title',
                'columns' => ['title' => 'Title', 'status' => 'Status', 'is_published' => 'Published', 'sort' => 'Order'],
                'search' => ['title', 'tagline'], 'order' => ['sort', 'asc'],
                'public' => 'products.show',
                'fields' => [
                    'title' => ['type' => 'text', 'required' => true],
                    'slug' => ['type' => 'text', 'help' => 'Leave empty to build it from the title.'],
                    'tagline' => ['type' => 'text'],
                    'status' => ['type' => 'select', 'options' => ['Live' => 'Live', 'Beta' => 'Beta', 'Coming soon' => 'Coming soon']],
                    'summary' => ['type' => 'textarea', 'max' => 500],
                    'body' => ['type' => 'markdown', 'label' => 'Full description'],
                    'url' => ['type' => 'url', 'label' => 'Product website'],
                    'cta_label' => ['type' => 'text', 'label' => 'Button text', 'help' => 'For example: Start your free trial'],
                    'features' => ['type' => 'list', 'help' => 'One per line. Use "Title | description" to add a short description.'],
                    'audiences' => ['type' => 'list', 'label' => 'Who it is for', 'help' => 'One per line.'],
                    'pricing_note' => ['type' => 'text'],
                    'logo' => ['type' => 'image'],
                    'image' => ['type' => 'image', 'label' => 'Screenshot'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_featured' => ['type' => 'bool', 'label' => 'Show on the home page'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ] + $seo,
            ],
            'projects' => [
                'model' => Models\Project::class,
                'label' => 'Projects', 'singular' => 'project', 'title' => 'title',
                'columns' => ['title' => 'Title', 'client' => 'Client', 'year' => 'Year', 'is_published' => 'Published'],
                'search' => ['title', 'client', 'industry'], 'order' => ['sort', 'asc'],
                'public' => 'work.show',
                'fields' => [
                    'title' => ['type' => 'text', 'required' => true],
                    'slug' => ['type' => 'text', 'help' => 'Leave empty to build it from the title.'],
                    'client' => ['type' => 'text'],
                    'industry' => ['type' => 'text'],
                    'year' => ['type' => 'text', 'max' => 10],
                    'summary' => ['type' => 'textarea', 'max' => 500],
                    'challenge' => ['type' => 'markdown', 'label' => 'The problem'],
                    'solution' => ['type' => 'markdown', 'label' => 'What we built'],
                    'results' => ['type' => 'markdown', 'label' => 'Outcome'],
                    'stack' => ['type' => 'list', 'label' => 'Technology used', 'help' => 'One per line.'],
                    'url' => ['type' => 'url', 'label' => 'Live link'],
                    'image' => ['type' => 'image', 'label' => 'Cover image'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_featured' => ['type' => 'bool', 'label' => 'Show on the home page'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ] + $seo,
            ],
            'posts' => [
                'model' => Models\Post::class,
                'label' => 'Insights', 'singular' => 'article', 'title' => 'title',
                'columns' => ['title' => 'Title', 'category' => 'Category', 'published_at' => 'Publish date', 'is_published' => 'Published'],
                'search' => ['title', 'category', 'excerpt'], 'order' => ['published_at', 'desc'],
                'public' => 'insights.show',
                'fields' => [
                    'title' => ['type' => 'text', 'required' => true],
                    'slug' => ['type' => 'text', 'help' => 'Leave empty to build it from the title.'],
                    'category' => ['type' => 'text'],
                    'excerpt' => ['type' => 'textarea', 'max' => 500],
                    'body' => ['type' => 'markdown', 'label' => 'Article'],
                    'image' => ['type' => 'image', 'label' => 'Cover image'],
                    'author' => ['type' => 'text'],
                    'published_at' => ['type' => 'datetime', 'label' => 'Publish date', 'help' => 'A future date schedules the article.'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ] + $seo,
            ],
            'team' => [
                'model' => Models\TeamMember::class,
                'label' => 'Team', 'singular' => 'team member', 'title' => 'name',
                'columns' => ['name' => 'Name', 'role' => 'Role', 'is_published' => 'Published', 'sort' => 'Order'],
                'search' => ['name', 'role'], 'order' => ['sort', 'asc'],
                'fields' => [
                    'name' => ['type' => 'text', 'required' => true],
                    'role' => ['type' => 'text'],
                    'bio' => ['type' => 'textarea'],
                    'photo' => ['type' => 'image'],
                    'linkedin' => ['type' => 'url', 'label' => 'LinkedIn link'],
                    'email' => ['type' => 'email'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ],
            ],
            'testimonials' => [
                'model' => Models\Testimonial::class,
                'label' => 'Testimonials', 'singular' => 'testimonial', 'title' => 'name',
                'columns' => ['name' => 'Name', 'company' => 'Company', 'is_published' => 'Published'],
                'search' => ['name', 'company', 'quote'], 'order' => ['sort', 'asc'],
                'fields' => [
                    'name' => ['type' => 'text', 'required' => true],
                    'role' => ['type' => 'text'],
                    'company' => ['type' => 'text'],
                    'quote' => ['type' => 'textarea', 'required' => true],
                    'photo' => ['type' => 'image'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ],
            ],
            'clients' => [
                'model' => Models\Client::class,
                'label' => 'Clients', 'singular' => 'client', 'title' => 'name',
                'columns' => ['name' => 'Name', 'url' => 'Website', 'is_published' => 'Published'],
                'search' => ['name'], 'order' => ['sort', 'asc'],
                'fields' => [
                    'name' => ['type' => 'text', 'required' => true],
                    'logo' => ['type' => 'image', 'help' => 'A transparent PNG works best.'],
                    'url' => ['type' => 'url', 'label' => 'Website'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ],
            ],
            'faqs' => [
                'model' => Models\Faq::class,
                'label' => 'FAQs', 'singular' => 'question', 'title' => 'question',
                'columns' => ['question' => 'Question', 'group' => 'Group', 'is_published' => 'Published'],
                'search' => ['question', 'answer'], 'order' => ['sort', 'asc'],
                'fields' => [
                    'question' => ['type' => 'text', 'required' => true],
                    'answer' => ['type' => 'textarea', 'required' => true],
                    'group' => ['type' => 'text', 'default' => 'General'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ],
            ],
            'jobs' => [
                'model' => Models\JobOpening::class,
                'label' => 'Careers', 'singular' => 'job opening', 'title' => 'title',
                'columns' => ['title' => 'Role', 'type' => 'Type', 'closes_at' => 'Closes', 'is_published' => 'Published'],
                'search' => ['title', 'location'], 'order' => ['sort', 'asc'],
                'public' => 'careers.show',
                'fields' => [
                    'title' => ['type' => 'text', 'required' => true],
                    'slug' => ['type' => 'text', 'help' => 'Leave empty to build it from the title.'],
                    'type' => ['type' => 'select', 'options' => ['Full-time' => 'Full-time', 'Part-time' => 'Part-time', 'Contract' => 'Contract', 'Internship' => 'Internship', 'NYSC' => 'NYSC']],
                    'location' => ['type' => 'text', 'default' => 'Kano, Nigeria'],
                    'summary' => ['type' => 'textarea', 'max' => 500],
                    'body' => ['type' => 'markdown', 'label' => 'Role description'],
                    'closes_at' => ['type' => 'date', 'label' => 'Closing date'],
                    'sort' => ['type' => 'number', 'label' => 'Display order'],
                    'is_published' => ['type' => 'bool', 'label' => 'Published', 'default' => true],
                ],
            ],
            'seo' => [
                'model' => Models\SeoPage::class,
                'label' => 'Page SEO', 'singular' => 'page', 'title' => 'label',
                'columns' => ['label' => 'Page', 'meta_title' => 'SEO title'],
                'search' => ['label', 'meta_title'], 'order' => ['id', 'asc'],
                'no_create' => true, 'no_delete' => true,
                'fields' => [
                    'label' => ['type' => 'text', 'required' => true, 'readonly' => true],
                    'meta_title' => ['type' => 'text', 'label' => 'SEO title', 'help' => 'Around 55 characters.'],
                    'meta_description' => ['type' => 'textarea', 'label' => 'SEO description', 'max' => 300, 'help' => 'Around 155 characters.'],
                    'og_image' => ['type' => 'image', 'label' => 'Share image', 'help' => 'Shown when the page is shared on WhatsApp, LinkedIn or X. 1200 × 630 works best.'],
                ],
            ],
        ];
    }

    public static function get(string $key): array
    {
        $all = static::all();
        abort_unless(isset($all[$key]), 404);

        return $all[$key] + ['key' => $key];
    }
}
