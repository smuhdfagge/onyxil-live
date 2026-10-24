<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\JobOpening;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\SeoPage;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Starting content. Safe to run again: it only adds what is missing
 * and never overwrites anything already edited in the admin.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->settings();
        $this->services();
        $this->products();
        $this->projects();
        $this->posts();
        $this->faqs();
        $this->jobs();
        $this->seoPages();
        Cache::forget('site_settings');
        Cache::forget('sitemap_xml');
    }

    protected function settings(): void
    {
        $defaults = [
            'site_name' => 'Onyx Innovations Ltd',
            'site_tagline' => 'Software, cloud and data systems for Nigerian organisations',
            'email' => 'info@onyxil.com',
            'phone' => '',
            'whatsapp' => '',
            'address' => 'Kano, Nigeria',
            'hours' => 'Monday to Friday, 8:30am – 5:30pm',
            'map_query' => 'Kano, Nigeria',
            'hero_title' => 'We build the systems behind the work.',
            'hero_text' => 'Onyx Innovations designs, builds and runs software, cloud and data systems for businesses, NGOs and public agencies across Nigeria. Then we stay on to keep them running.',
            'hero_primary' => 'Start a project',
            'hero_secondary' => 'See our work',
            'stats' => '',
            'cta_title' => 'Have something that needs building?',
            'cta_text' => 'Tell us what you are trying to fix. We will reply within one working day with honest questions, not a sales pitch.',
            'about_intro' => 'Onyx Innovations Limited is an information technology company. We design, build and manage software, cloud and network infrastructure, and the data systems organisations rely on to make sound decisions. We also develop and run our own products, including MyBooks, a cloud accounting platform for growing businesses.',
            'about_story' => "Onyx started from a simple observation: a lot of organisations in Northern Nigeria were being sold technology that did not fit how they actually work. Systems were bought, launched and then left alone. When the person who set them up moved on, the knowledge went with them.\n\nWe set out to do it differently. We spend time understanding the work before we write any code. We build in plain, well-known technology so that you are never locked to us. And we stay on after launch, because most of the value of a system shows up in the months after it goes live, not on launch day.\n\nToday we work with private companies, NGOs and public institutions, and we run our own software products. MyBooks, our cloud accounting platform, came out of the same habit: we kept seeing Nigerian businesses struggle with accounting tools that did not understand VAT, WHT or PAYE, so we built one that does.",
            'mission' => 'To give Nigerian organisations software and systems they can rely on, understand and own.',
            'vision' => 'To be a technology partner that organisations across Africa and beyond trust with the systems that matter most to them.',
            'values' => "Build for the real work | We learn how your team actually works before we design anything.\nNo lock-in | Standard tools, clean code and full handover, so you are never stuck with us.\nStay after launch | Support, fixes and improvements are part of the job, not an upsell.\nSay it plainly | Clear prices, clear timelines and straight answers when something goes wrong.",
            'seo_title_suffix' => '| Onyx Innovations',
            'seo_description' => 'Onyx Innovations Ltd is a Kano-based ICT company building custom software, websites, mobile apps, cloud hosting and data systems for businesses, NGOs and government in Nigeria.',
            'seo_keywords' => 'IT company in Kano, software development Nigeria, web design Kano, cloud hosting Nigeria, MIS, dashboards, MyBooks, accounting software Nigeria',
            'privacy_policy' => $this->privacy(),
            'terms' => $this->terms(),
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    protected function services(): void
    {
        $items = [
            [
                'title' => 'Custom software development',
                'icon' => 'code',
                'summary' => 'Business systems built around how your organisation works: approvals, records, payments, reports and the rules that tie them together.',
                'body' => "Off-the-shelf software is fine until your process does not fit it. Then people start working around the system with spreadsheets, WhatsApp groups and paper files, and the system becomes a record of what should have happened.\n\nWe build software around the way your team actually works. That usually means sitting with the people who will use it, mapping each step, and then building something that removes steps instead of adding them.\n\n## How we work\n\nWe start with a short discovery phase to write down what the system must do and what it must not do. We then build in small pieces, showing you working software every two weeks, so you can change direction early when it is still cheap to do so.\n\n## What we build with\n\nWe use well-known, well-supported tools such as Laravel, PHP, JavaScript and MySQL or PostgreSQL. Any competent developer can pick up the code after us. You own the code and the data.",
                'features' => ['Discovery and requirements workshops', 'Workflow and approval systems', 'Records, registers and case management', 'Payment and invoicing integrations, including Paystack and Flutterwave', 'Role-based access and audit logs', 'Training and handover documentation'],
                'deliverables' => ['Working system on your own server or cloud account', 'Full source code in your own repository', 'Admin and user guides', '90 days of support after launch'],
            ],
            [
                'title' => 'Websites and web applications',
                'icon' => 'spark',
                'summary' => 'Fast, well-built websites that load on slow networks, rank on Google and are easy for your team to update.',
                'body' => "Most visitors will judge your organisation by your website before they ever speak to you, and most of them will be on a phone with an unreliable connection.\n\nWe build websites that load quickly on mobile data, read clearly on a small screen, and show up when people search for what you do. Every site comes with a simple admin so your team can change text, add news and publish new pages without calling us.\n\n## Built to be found\n\nSearch engines reward the same things people do: speed, clear structure and useful content. We set up page titles, descriptions, structured data, sitemaps and Google Search Console as standard, not as an extra.",
                'features' => ['Design based on your brand', 'Content management admin', 'Search engine set-up as standard', 'Contact, quote and booking forms', 'Blog and news sections', 'Analytics and Search Console set-up'],
                'deliverables' => ['Live website on your domain', 'Admin login for your team', 'Short video walkthrough of the admin', 'Search engine and analytics set-up'],
            ],
            [
                'title' => 'Mobile apps',
                'icon' => 'mobile',
                'summary' => 'Android and iOS apps for customers, field staff and agents, including apps that keep working without a network.',
                'body' => "Field teams in Nigeria often work where the network drops for hours. An app that only works online is an app that will not be used.\n\nWe build mobile apps that save work on the phone and sync when a connection comes back, so data collected in a village or a warehouse is never lost. For customer-facing apps, we focus on fast start-up, small download size and clear flows that work for first-time smartphone users.\n\n## Field data collection\n\nFor surveys, enrolment and monitoring, we can build on proven tools such as ODK and KoboToolbox, or build a custom app when the job needs it. Either way, the data lands in a system you control.",
                'features' => ['Android and iOS apps', 'Offline-first data capture with sync', 'GPS, photo and signature capture', 'Push notifications', 'Integration with your web system', 'Play Store and App Store publishing'],
                'deliverables' => ['Published app in the stores or as a direct install', 'Back-end API and admin', 'Device testing report', 'Support after launch'],
            ],
            [
                'title' => 'Cloud hosting and DevOps',
                'icon' => 'cloud',
                'summary' => 'Hosting, email, backups and monitoring set up properly, so your systems stay up and your data stays safe.',
                'body' => "A system is only useful when it is running. We set up and look after the infrastructure behind your websites and applications, whether that is a shared hosting account, a VPS or a full cloud set-up.\n\n## What looking after it means\n\nAutomated daily backups that are tested, not just scheduled. Monitoring that tells us when something goes down before your users do. Security updates applied on a schedule. SSL certificates renewed automatically. A clear record of who has access to what.\n\nWe also help organisations move off servers sitting in a back office and onto reliable cloud hosting, without losing data on the way.",
                'features' => ['Cloud and VPS set-up', 'Business email on your domain', 'Automated, tested backups', 'Uptime monitoring and alerts', 'SSL, firewall and security updates', 'Migration from old servers'],
                'deliverables' => ['Documented hosting set-up in your name', 'Backup and recovery plan', 'Monthly status report on managed plans'],
            ],
            [
                'title' => 'Data systems, MIS and dashboards',
                'icon' => 'chart',
                'summary' => 'Management information systems, data pipelines and dashboards that turn scattered records into numbers you can act on.',
                'body' => "Many organisations collect a lot of data and still cannot answer simple questions quickly. How many people did we reach last month? Which locations are behind? Where did the money go?\n\nWe design management information systems that keep records clean from the point of entry, check them automatically, and present them in dashboards and reports that managers, funders and auditors can trust.\n\n## Built for reporting\n\nWe have experience with the kind of reporting that donors, development partners and government bodies expect: disaggregated figures, clear audit trails, and exports in the formats they ask for. We also clean and merge existing data, including large spreadsheets that have grown out of control.",
                'features' => ['MIS design and build', 'Data cleaning, de-duplication and validation', 'Dashboards and scheduled reports', 'Integration of field data, payments and registers', 'Excel, PDF and API exports', 'Data protection and access control'],
                'deliverables' => ['Working MIS or dashboard', 'Data dictionary', 'Reporting templates', 'Training for your data team'],
            ],
            [
                'title' => 'Networks and IT infrastructure',
                'icon' => 'network',
                'summary' => 'Office networks, Wi-Fi, CCTV and hardware, planned and installed so they work every day without fuss.',
                'body' => "Slow Wi-Fi, a printer nobody can connect to and cables everywhere cost more time than most offices realise.\n\nWe plan, supply and install structured cabling, Wi-Fi, switches, firewalls, CCTV and access control. We label everything and document it, so the next person to touch it knows what they are looking at.\n\n## Support contracts\n\nFor organisations without in-house IT staff, we offer monthly support covering helpdesk, maintenance visits and hardware advice.",
                'features' => ['Structured cabling and Wi-Fi', 'Firewalls and network security', 'CCTV and access control', 'Hardware supply and set-up', 'Power backup planning', 'Monthly support contracts'],
                'deliverables' => ['Network diagram and documentation', 'Labelled installation', 'Admin passwords handed over securely'],
            ],
            [
                'title' => 'Cybersecurity',
                'icon' => 'shield',
                'summary' => 'Security reviews, staff training and practical fixes that close the gaps attackers actually use.',
                'body' => "Most breaches in small and mid-sized organisations do not start with a clever hack. They start with a reused password, an unpatched server or a staff member who clicked the wrong link.\n\nWe review your systems, accounts and habits, then give you a short, prioritised list of what to fix first. We can carry out the fixes ourselves and train your team so the same problems do not come back.\n\n## Data protection\n\nWe help organisations put sensible controls in place to meet the Nigeria Data Protection Act 2023, including access control, data retention and breach response.",
                'features' => ['Security review of systems and accounts', 'Two-step sign-in roll-out', 'Website and server hardening', 'Phishing awareness training', 'Backup and recovery planning', 'Data protection support'],
                'deliverables' => ['Plain-language findings report', 'Prioritised fix list', 'Staff training session'],
            ],
            [
                'title' => 'IT consulting and digital transformation',
                'icon' => 'compass',
                'summary' => 'Independent advice on what to build, what to buy and how to roll it out, before you commit the budget.',
                'body' => "The most expensive technology mistakes happen before any software is written: the wrong product bought, the wrong vendor chosen, or a project scoped so loosely that it never ends.\n\nWe help leadership teams think through technology decisions. That can mean reviewing a proposal you have received, writing the specification for a tender, comparing products, or planning how to move a paper process onto a system without disrupting the work.\n\n## For public institutions and NGOs\n\nWe understand procurement rules, donor requirements and the reality of rolling out systems across many locations. We can support the whole process, from needs assessment to supervising the vendor.",
                'features' => ['Technology strategy and roadmaps', 'Requirements and tender specifications', 'Vendor and proposal reviews', 'Process mapping and digitisation', 'Project supervision', 'Staff training and change support'],
                'deliverables' => ['Written recommendation or specification', 'Budget and timeline estimate', 'Roll-out plan'],
            ],
        ];

        foreach ($items as $i => $item) {
            $slug = \Illuminate\Support\Str::slug($item['title']);
            if (! Service::where('slug', $slug)->exists()) {
                Service::create($item + ['slug' => $slug, 'sort' => $i + 1, 'is_featured' => true, 'is_published' => true]);
            }
        }
    }

    protected function products(): void
    {
        if (Product::where('slug', 'mybooks')->exists()) {
            return;
        }

        Product::create([
            'title' => 'MyBooks',
            'slug' => 'mybooks',
            'tagline' => 'Invoices out, money in, tax worked out.',
            'status' => 'Live',
            'summary' => 'Cloud accounting for Nigerian businesses. Invoices, VAT, WHT, PAYE, payroll, stock and reports in one place.',
            'body' => "Most accounting software was written for other countries and then adjusted for Nigeria as an afterthought. MyBooks was built here, for the way Nigerian businesses buy, sell, pay staff and file tax.\n\nBusiness owners can check money in, money out and what is due from their phone. Accountants get a proper double-entry ledger, reports and month-end tools. Staff only see the parts of the system their role allows.\n\n## Tax handled properly\n\nVAT at 7.5%, withholding tax, PAYE and pension are worked out as you go, so tax time is a report, not a project. E-invoicing support is being prepared.\n\n## Bring your existing records\n\nYou do not have to start from zero. Customers, suppliers, items, opening balances, invoices and employees can all be imported from spreadsheets.\n\n## Your data is safe\n\nMyBooks runs over HTTPS with two-step sign-in. Each business's data is kept separate, access is controlled by role, and you can download a full backup at any time.",
            'url' => 'https://mybooks.cloud',
            'cta_label' => 'Visit mybooks.cloud',
            'features' => [
                'Invoicing | Send invoices with automatic email payment reminders and customer statements.',
                'Nigerian tax | VAT at 7.5%, WHT, PAYE and pension worked out as you go.',
                'Payroll | Run payroll, send payslips and track staff leave.',
                'Stock | Manage inventory across more than one warehouse.',
                'Purchasing | Purchase orders, supplier bills and payments in one flow.',
                'Reports | Profit, cash, money owed to you and money you owe, any time.',
                'Proper books | Double-entry ledger, bank reconciliation, fixed assets and month locking.',
                'Budgets | Set budgets and track spending against them.',
                'Easy import | Bring customers, suppliers, items and balances in from spreadsheets.',
                'Secure | Two-step sign-in, role-based access, activity log and full backup download.',
            ],
            'audiences' => [
                'Business owners | See money in, money out and what is due, from your phone.',
                'Accountants | The ledger, reports and month-end tools you expect.',
                'Staff | Only the parts of the system their role allows.',
            ],
            'pricing_note' => 'Plans from ₦250,000 a year for up to 5 users. Pay monthly or yearly; a year costs the same as ten months.',
            'sort' => 1,
            'is_featured' => true,
            'is_published' => true,
            'meta_title' => 'MyBooks — accounting software for Nigerian businesses',
            'meta_description' => 'MyBooks is cloud accounting built for Nigeria: invoices, VAT, WHT, PAYE, payroll, stock and reports in one place. Built and run by Onyx Innovations.',
        ]);
    }

    protected function projects(): void
    {
        $items = [
            [
                'title' => 'MyBooks cloud accounting platform',
                'slug' => 'mybooks-cloud-accounting',
                'client' => 'Onyx Innovations (own product)',
                'industry' => 'Finance and accounting',
                'year' => '2026',
                'summary' => 'A multi-business accounting platform built for Nigerian tax rules, now live at mybooks.cloud.',
                'challenge' => "Small and growing Nigerian businesses were keeping their books in spreadsheets or in foreign accounting tools that did not understand VAT, withholding tax or PAYE. Owners could not see their cash position without asking an accountant, and tax season meant weeks of reconstruction.",
                'solution' => "We designed and built MyBooks as a multi-tenant cloud platform where each business's data is kept separate. It covers invoicing, purchasing, stock across warehouses, payroll and a full double-entry ledger, with Nigerian tax built into every transaction.\n\nSubscriptions are handled through Paystack, and businesses can import their existing records from spreadsheets so they do not start from zero.",
                'results' => "MyBooks is live at mybooks.cloud with three plans for businesses of different sizes. We continue to ship improvements, with e-invoicing support in preparation.",
                'stack' => ['Laravel', 'PHP 8.4', 'MySQL', 'Paystack', 'Multi-tenant architecture'],
                'url' => 'https://mybooks.cloud',
            ],
            [
                'title' => 'Ammarco Travels website',
                'slug' => 'ammarco-travels-website',
                'client' => 'Ammarco Travels',
                'industry' => 'Travel and tourism',
                'year' => '2026',
                'summary' => 'A clear, mobile-first website for a travel agency, presenting its services and making it easy for customers to get in touch.',
                'challenge' => "The agency needed a professional web presence that explained its services clearly and turned visitors into enquiries, with most traffic expected from phones.",
                'solution' => "We built a Laravel website with focused pages for the agency's story, its services and a contact flow designed for mobile visitors.",
                'results' => "The agency now has a fast, easy-to-maintain website to point customers to from social media and print.",
                'stack' => ['Laravel', 'Blade', 'Responsive design'],
            ],
            [
                'title' => 'SWATCH NGO website',
                'slug' => 'swatch-ngo-website',
                'client' => 'Support for Women and Teenage Children (SWATCH)',
                'industry' => 'Non-profit',
                'year' => '2026',
                'summary' => 'A rebuilt website for a Kano NGO, designed to present its programmes clearly to donors and partners.',
                'challenge' => "SWATCH needed a website that could stand up to scrutiny from donors and partners: clear about its programmes, its reach and how to support the work.",
                'solution' => "We rebuilt the site in Laravel with an admin the team can update themselves, and wrote the structure around the questions donors ask first.",
                'results' => "The organisation has a donor-ready website it can keep current without outside help.",
                'stack' => ['Laravel', 'Content admin', 'SEO set-up'],
                'url' => 'https://swatch.org.ng',
            ],
        ];

        foreach ($items as $i => $item) {
            if (! Project::where('slug', $item['slug'])->exists()) {
                Project::create($item + ['sort' => $i + 1, 'is_featured' => true, 'is_published' => true]);
            }
        }
    }

    protected function posts(): void
    {
        $items = [
            [
                'title' => 'VAT, WHT and PAYE: what your accounting software should do for you',
                'slug' => 'vat-wht-paye-accounting-software-nigeria',
                'category' => 'Business',
                'excerpt' => 'If your books cannot tell you what you owe in tax at the end of each month, the software is not doing its job. Here is what to look for.',
                'body' => "Every Nigerian business deals with at least three taxes in its day-to-day books: VAT on sales and purchases, withholding tax on certain payments, and PAYE on staff salaries. Many businesses only work these out at the end of the year, usually in a hurry.\n\nGood accounting software should make that unnecessary. Here is what to expect from it.\n\n## VAT on every invoice and bill\n\nVAT in Nigeria is 7.5%. Your software should apply it when you raise an invoice, record it when you receive a bill, and keep a running figure of what you owe or can reclaim. At the end of the month, the VAT return should be a report you print, not a spreadsheet you build.\n\n## Withholding tax that is not forgotten\n\nWithholding tax is easy to miss because it is deducted from payments rather than added to them. Your software should let you mark which suppliers and transactions attract WHT, deduct it at the point of payment and track what you need to remit, so you are not caught out later.\n\n## PAYE and pension from payroll\n\nPayroll should calculate PAYE and pension contributions for each employee, produce payslips, and post the totals to your ledger automatically. If someone is retyping payroll figures into the accounts, mistakes will creep in.\n\n## Reports you can hand to your accountant\n\nAt minimum: profit and loss, balance sheet, cash flow, aged receivables and payables, and a tax summary. Your accountant should be able to log in with their own account and see these directly.\n\n## Keep up with changes\n\nNigeria's tax rules have been reformed in recent years, and further changes such as mandatory e-invoicing are coming. Choose software built and maintained by people who follow these changes closely, so updates arrive in the product rather than as a surprise.\n\nWe built [MyBooks](/products/mybooks) to do all of the above for Nigerian businesses.",
                'author' => 'Onyx Innovations',
                'days' => 6,
            ],
            [
                'title' => 'Before you hire a developer: seven questions to answer first',
                'slug' => 'before-you-hire-a-developer',
                'category' => 'Software projects',
                'excerpt' => 'Most software projects that go wrong were unclear before they started. Answer these seven questions and your project, and your quote, will be far better.',
                'body' => "When a software project runs over budget or never finishes, the cause is rarely the code. Usually, nobody wrote down clearly what the system had to do. These seven questions will save you money whoever you hire.\n\n## 1. What problem are you solving?\n\nDescribe it in one or two sentences without mentioning technology. \"Our field officers spend two days a week retyping paper forms\" is a problem. \"We need an app\" is not.\n\n## 2. Who will use it, and how often?\n\nList each type of user: customers, staff, managers, partners. For each, note what they need to do and whether they will use it daily or once a month.\n\n## 3. What happens today?\n\nWalk through the current process step by step, including the spreadsheets, paper forms and WhatsApp messages. This is where the real requirements hide.\n\n## 4. What must it connect to?\n\nPayments, SMS, email, an existing database, a government portal? Each connection adds work, so list them early.\n\n## 5. What does success look like in six months?\n\nPick one or two measurable outcomes, such as hours saved, errors reduced or payments collected faster.\n\n## 6. Who decides?\n\nName one person on your side who can make decisions quickly. Projects stall when every question waits for a meeting.\n\n## 7. What happens after launch?\n\nWho will host it, fix it, update it and train new staff? Budget for this from the start. A system nobody maintains slowly stops working.\n\nIf you would like help answering these, [tell us about your project](/start-a-project). We often do this as a short paid discovery before any building starts.",
                'author' => 'Onyx Innovations',
                'days' => 14,
            ],
            [
                'title' => 'Your organisation\'s data should not live on one person\'s laptop',
                'slug' => 'organisation-data-not-on-one-laptop',
                'category' => 'Data and cloud',
                'excerpt' => 'A stolen laptop or a staff member leaving should never mean losing years of records. A few simple changes remove that risk.',
                'body' => "In many offices, the most important records sit in one place: a spreadsheet on one officer's laptop. It works, until the laptop is stolen, the hard drive fails or that officer resigns.\n\n## Why this keeps happening\n\nSpreadsheets are easy to start and hard to outgrow. One person builds a tracker, it becomes useful, other people start sending them updates, and before long the whole organisation depends on a file nobody else fully understands.\n\n## Three changes that fix most of it\n\n**Move shared files to a shared, backed-up location.** A properly set-up cloud drive with the right sharing permissions is enough for many teams. It costs little and removes the single point of failure.\n\n**Turn the most important spreadsheet into a simple system.** If many people update the same records, a small web system with proper user accounts, validation and history will save time and prevent the copy-of-a-copy problem.\n\n**Back up, and test the backup.** A backup you have never restored is a hope, not a plan. Schedule a test restore every few months.\n\n## A note on data protection\n\nThe Nigeria Data Protection Act 2023 places duties on organisations that hold personal data. Knowing where your data lives and who can reach it is the first step to meeting them.\n\nIf your records have outgrown your spreadsheets, our [data systems team](/services/data-systems-mis-and-dashboards) can help.",
                'author' => 'Onyx Innovations',
                'days' => 25,
            ],
        ];

        foreach ($items as $item) {
            $days = $item['days'];
            unset($item['days']);
            if (! Post::where('slug', $item['slug'])->exists()) {
                Post::create($item + ['published_at' => now()->subDays($days), 'is_published' => true]);
            }
        }
    }

    protected function faqs(): void
    {
        if (Faq::exists()) {
            return;
        }

        $items = [
            ['General', 'How much does a project cost?', 'It depends on what you need. After a short call, we send a written estimate with a cost range and a timeline. For larger systems we usually start with a small paid discovery phase so the estimate is based on facts, not guesses.'],
            ['General', 'How long does a website or system take?', 'A company website usually takes three to six weeks. A custom system takes longer, and we deliver it in stages so you can start using parts of it early.'],
            ['General', 'Do you work with clients outside Kano?', 'Yes. We work with organisations across Nigeria, and most of the work can be done remotely with site visits when they are needed.'],
            ['General', 'Who owns the code and the data?', 'You do. We hand over the source code, the hosting accounts and all the data. We build with standard tools so another developer can take over if you ever need them to.'],
            ['General', 'Do you support systems after launch?', 'Yes. Every project includes a support period after launch, and we offer monthly support plans for hosting, updates, fixes and small improvements.'],
            ['General', 'Can you take over a system someone else built?', 'Usually, yes. We start with a review of the code and the hosting, tell you plainly what condition it is in, and then agree how to support or improve it.'],
            ['MyBooks', 'What does MyBooks cost?', 'Starter is ₦250,000 a year for up to 5 users, Professional is ₦300,000 a year for up to 15 users, and Enterprise is ₦600,000 a year for up to 50 users. You can pay monthly or yearly through Paystack.'],
            ['MyBooks', 'Can I bring in my existing records?', 'Yes. Customers, suppliers, items, opening balances, invoices and employees can be imported from spreadsheets.'],
            ['MyBooks', 'Is my business data kept private?', 'Each business on MyBooks has its own separate data. Access is controlled by role, sign-in supports two-step verification, and you can download a full backup at any time.'],
        ];

        foreach ($items as $i => [$group, $q, $a]) {
            Faq::create(['group' => $group, 'question' => $q, 'answer' => $a, 'sort' => $i + 1, 'is_published' => true]);
        }
    }

    protected function jobs(): void
    {
        if (JobOpening::exists()) {
            return;
        }

        JobOpening::create([
            'title' => 'Laravel developer',
            'slug' => 'laravel-developer',
            'type' => 'Full-time',
            'location' => 'Kano, Nigeria (hybrid)',
            'summary' => 'Build and maintain client systems and our own products, including MyBooks.',
            'body' => "## What you will do\n\n- Build features in Laravel applications for clients and for our own products\n- Write clear, tested code that someone else can maintain\n- Work directly with clients to understand what they need\n- Help look after the servers our systems run on\n\n## What we are looking for\n\n- At least two years building with PHP and Laravel\n- Comfort with MySQL, Git and Linux servers\n- Clear written English\n- Evidence of work you have shipped. A GitHub link or live project matters more to us than a certificate\n\n## How to apply\n\nSend your CV and links to your work to the email address on our contact page, with the role title in the subject line.",
            'sort' => 1,
            'is_published' => true,
        ]);

        JobOpening::create([
            'title' => 'IT support intern',
            'slug' => 'it-support-intern',
            'type' => 'Internship',
            'location' => 'Kano, Nigeria',
            'summary' => 'Six months supporting networks, hardware and users for our clients, with mentoring from our team.',
            'body' => "## What you will do\n\n- Help install and maintain networks, computers and printers at client sites\n- Answer helpdesk requests and keep clear notes of what was fixed\n- Learn how we set up hosting, backups and security\n\n## Who this suits\n\n- Recent graduates, SIWES students or NYSC members in computing or a related field\n- People who like solving practical problems and explaining things simply\n\n## How to apply\n\nSend a short note about yourself and your CV to the email address on our contact page.",
            'sort' => 2,
            'is_published' => true,
        ]);
    }

    protected function seoPages(): void
    {
        $pages = [
            'home' => 'Home page', 'about' => 'About', 'services' => 'Services', 'products' => 'Products',
            'work' => 'Our work', 'insights' => 'Insights', 'careers' => 'Careers', 'contact' => 'Contact',
            'quote' => 'Start a project',
        ];
        foreach ($pages as $page => $label) {
            SeoPage::firstOrCreate(['page' => $page], ['label' => $label]);
        }
    }

    protected function privacy(): string
    {
        return "This policy explains what personal data Onyx Innovations Limited collects through this website, why we collect it and how we look after it. We process personal data in line with the Nigeria Data Protection Act 2023.\n\n## What we collect\n\nWhen you fill in our contact or project forms, we collect the details you give us: your name, email address, phone number, organisation and the content of your message. When you subscribe to updates, we collect your email address. Like most websites, our server also records basic technical information such as your IP address and browser type.\n\n## Why we collect it\n\nWe use your details to reply to your enquiry, prepare proposals, and send updates you have asked for. We do not sell your data or share it with third parties for their marketing.\n\n## How long we keep it\n\nWe keep enquiries for as long as we need them to deal with your request and any work that follows, and for any period the law requires. You can unsubscribe from updates at any time.\n\n## Your rights\n\nYou can ask to see the personal data we hold about you, ask us to correct it or ask us to delete it. Contact us using the details on our contact page.\n\n## Cookies\n\nThis website uses a small number of cookies that it needs to work, such as a session cookie. If analytics is turned on, it uses cookies to understand how the site is used in aggregate.\n\n## Changes\n\nWe may update this policy from time to time. The latest version will always be on this page.";
    }

    protected function terms(): string
    {
        return "These terms apply to your use of this website, operated by Onyx Innovations Limited.\n\n## Using this website\n\nYou may browse and share content from this website for personal or business reference, with credit to us. You may not copy the website's design or content to present it as your own.\n\n## Information on this website\n\nWe work to keep the information here accurate and current, but it is provided for general information. It does not form an offer or a contract. Any work we carry out for you will be covered by a separate written agreement.\n\n## Products\n\nOur software products, including MyBooks, have their own terms of service, which apply when you sign up to use them.\n\n## Links to other websites\n\nWe are not responsible for the content of websites we link to.\n\n## Contact\n\nIf you have questions about these terms, contact us using the details on our contact page.";
    }
}
