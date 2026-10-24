# Onyx Innovations Ltd — website

The public website and admin panel for Onyx Innovations Limited, built with Laravel 13.

- **Public site:** home, about, services, products (MyBooks), work, insights (blog), careers, contact, start-a-project, privacy and terms.
- **Admin panel** at `/admin`: edit every page and section without touching code, read messages and project enquiries, manage subscribers, upload images, set SEO for each page, and add other admins.
- **Search engines:** page titles and descriptions, canonical links, Open Graph and X cards for sharing, structured data (Organization, WebSite, Service, SoftwareApplication, BlogPosting, JobPosting, FAQ, breadcrumbs), a live `sitemap.xml`, `robots.txt` and an RSS feed at `/feed.xml`.
- **Speed:** no front-end build step, self-hosted fonts, images converted to WebP and resized on upload, long browser caching and compression in `public/.htaccess`.

## Requirements

- PHP 8.3 or newer (8.4 recommended) with the GD, mbstring, PDO MySQL, fileinfo and openssl extensions
- MySQL 5.7+ or MariaDB 10.3+ (SQLite works for local testing)
- Composer

Node and npm are **not** needed.

## Install on the server

```bash
git clone https://github.com/smuhdfagge/onyxil-live.git
cd onyxil-live
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edit `.env`: set `APP_URL` to the real domain, fill in the `DB_` lines, and set `ADMIN_EMAIL` (and optionally `ADMIN_PASSWORD`). Then:

```bash
php artisan migrate --force --seed
php artisan optimize
```

The seed step fills the site with starting content and creates the first admin. If `ADMIN_PASSWORD` was left empty, a strong password is printed once in the terminal. Copy it.

Point the domain's document root at the `public` folder. On shared hosting where you cannot change the document root, copy `.htaccess.root-example` to the project root as `.htaccess`.

Make sure the web server can write to `storage/`, `bootstrap/cache/` and `public/uploads/`.

### Sending enquiry emails

Every message and project enquiry is saved in the admin. To also receive them by email, fill in the `MAIL_` lines in `.env` with your mail provider's SMTP details, then set **Settings → Notifications** in the admin.

## Everyday admin tasks

| Task | Where |
| --- | --- |
| Change phone, email, address, WhatsApp, social links | Admin → Settings |
| Change the home page headline and closing message | Admin → Settings → Home page |
| Add figures to the home page (e.g. "40+ systems delivered") | Admin → Settings → Home page → Figures strip |
| Add a project, article, job, team member or testimonial | Admin → the matching menu item → Add |
| Set the Google title and description of a page | Admin → Page SEO, or the "Search engines" box on any item |
| Connect Google Search Console and Analytics | Admin → Settings → SEO and tracking |

Sections with nothing in them (team, testimonials, clients, figures) stay hidden on the site until you add content.

## Useful commands

```bash
php artisan onyx:admin you@example.com            # create an admin or reset a password (prints it)
php artisan onyx:admin you@example.com --password=YourNewPassword
php artisan db:seed --force                        # add any missing starting content; never overwrites your edits
php artisan optimize                               # run after every deploy
```

## After going live

1. Add the site to Google Search Console and submit `https://your-domain/sitemap.xml`.
2. Paste the verification code into Admin → Settings → SEO and tracking.
3. Replace the placeholder contact details in Settings with the real ones.
4. Add real testimonials, team members and client logos.

## Code map

- `routes/web.php` — every URL on the site and in the admin
- `app/Admin/Resources.php` — the list of editable content types and their fields; the admin forms are built from this
- `app/Admin/SettingsSchema.php` — the fields on the Settings page
- `app/Support/Seo.php` — page titles, descriptions and structured data
- `resources/views/site` — public pages; `resources/views/admin` — admin pages
- `public/css/site.css`, `public/js/site.js` — the design and animations
- `database/seeders/ContentSeeder.php` — the starting content
