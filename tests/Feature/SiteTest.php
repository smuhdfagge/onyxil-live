<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_public_pages_load(): void
    {
        foreach (['/', '/about', '/services', '/services/mobile-apps', '/products', '/products/mybooks', '/work',
            '/work/mybooks-cloud-accounting', '/insights', '/insights/before-you-hire-a-developer', '/careers',
            '/careers/laravel-developer', '/contact', '/start-a-project', '/privacy', '/terms',
            '/sitemap.xml', '/robots.txt', '/feed.xml', '/site.webmanifest'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/no-such-page')->assertNotFound();
    }

    public function test_contact_form_saves_a_message(): void
    {
        $this->post('/contact', [
            'name' => 'Amina Bello', 'email' => 'amina@example.com',
            'message' => 'We need a new website for our school.', '_started' => time() - 30,
        ])->assertRedirect('/contact');

        $this->assertSame(1, Message::count());
    }

    public function test_honeypot_blocks_bots(): void
    {
        $this->post('/start-a-project', [
            'name' => 'Bot', 'email' => 'bot@example.com', 'details' => str_repeat('spam ', 10), 'website_url' => 'http://spam.test',
        ])->assertRedirect('/start-a-project');

        $this->assertSame(0, QuoteRequest::count());
    }

    public function test_admin_requires_sign_in(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_pages_load_for_admins(): void
    {
        $this->actingAs(User::first());
        foreach (['/admin', '/admin/messages', '/admin/quotes', '/admin/subscribers', '/admin/settings', '/admin/account',
            '/admin/services', '/admin/services/create', '/admin/services/1/edit', '/admin/posts', '/admin/seo'] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
