<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/* Public site */
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/services', [SiteController::class, 'services'])->name('services.index');
Route::get('/services/{service}', [SiteController::class, 'service'])->name('services.show');
Route::get('/products', [SiteController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [SiteController::class, 'product'])->name('products.show');
Route::get('/work', [SiteController::class, 'work'])->name('work.index');
Route::get('/work/{project}', [SiteController::class, 'project'])->name('work.show');
Route::get('/insights', [SiteController::class, 'insights'])->name('insights.index');
Route::get('/insights/{post}', [SiteController::class, 'post'])->name('insights.show');
Route::get('/careers', [SiteController::class, 'careers'])->name('careers.index');
Route::get('/careers/{job}', [SiteController::class, 'job'])->name('careers.show');
Route::get('/{page}', [SiteController::class, 'legal'])->whereIn('page', ['privacy', 'terms'])->name('legal');

Route::get('/contact', [EnquiryController::class, 'contact'])->name('contact');
Route::post('/contact', [EnquiryController::class, 'sendContact'])->middleware('throttle:6,10')->name('contact.send');
Route::get('/start-a-project', [EnquiryController::class, 'quote'])->name('quote');
Route::post('/start-a-project', [EnquiryController::class, 'sendQuote'])->middleware('throttle:6,10')->name('quote.send');
Route::post('/subscribe', [EnquiryController::class, 'subscribe'])->middleware('throttle:10,10')->name('subscribe');

/* Search engines */
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots']);
Route::get('/feed.xml', [SitemapController::class, 'feed'])->name('feed');
Route::get('/site.webmanifest', [SitemapController::class, 'manifest']);

/* Admin */
Route::get('/admin/login', [Admin\AuthController::class, 'show'])->name('login');
Route::post('/admin/login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::get('/messages', [Admin\InboxController::class, 'messages'])->name('messages');
    Route::get('/messages/{message}', [Admin\InboxController::class, 'message'])->name('messages.show');
    Route::post('/messages/{message}/unread', [Admin\InboxController::class, 'markUnread'])->name('messages.unread');
    Route::delete('/messages/{message}', [Admin\InboxController::class, 'destroyMessage'])->name('messages.destroy');

    Route::get('/quotes', [Admin\InboxController::class, 'quotes'])->name('quotes');
    Route::get('/quotes/{quote}', [Admin\InboxController::class, 'quote'])->name('quotes.show');
    Route::put('/quotes/{quote}', [Admin\InboxController::class, 'updateQuote'])->name('quotes.update');
    Route::delete('/quotes/{quote}', [Admin\InboxController::class, 'destroyQuote'])->name('quotes.destroy');

    Route::get('/subscribers', [Admin\InboxController::class, 'subscribers'])->name('subscribers');
    Route::get('/subscribers/export', [Admin\InboxController::class, 'exportSubscribers'])->name('subscribers.export');
    Route::delete('/subscribers/{subscriber}', [Admin\InboxController::class, 'destroySubscriber'])->name('subscribers.destroy');

    Route::get('/settings', [Admin\SettingsController::class, 'edit'])->name('settings');
    Route::put('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');

    Route::get('/account', [Admin\AccountController::class, 'index'])->name('account');
    Route::put('/account', [Admin\AccountController::class, 'update'])->name('account.update');
    Route::post('/account/users', [Admin\AccountController::class, 'store'])->name('account.users.store');
    Route::delete('/account/users/{user}', [Admin\AccountController::class, 'destroy'])->name('account.users.destroy');

    Route::get('/{resource}', [Admin\ResourceController::class, 'index'])->name('resource.index');
    Route::get('/{resource}/create', [Admin\ResourceController::class, 'create'])->name('resource.create');
    Route::post('/{resource}', [Admin\ResourceController::class, 'store'])->name('resource.store');
    Route::get('/{resource}/{id}/edit', [Admin\ResourceController::class, 'edit'])->whereNumber('id')->name('resource.edit');
    Route::put('/{resource}/{id}', [Admin\ResourceController::class, 'update'])->whereNumber('id')->name('resource.update');
    Route::post('/{resource}/{id}/toggle', [Admin\ResourceController::class, 'toggle'])->whereNumber('id')->name('resource.toggle');
    Route::delete('/{resource}/{id}', [Admin\ResourceController::class, 'destroy'])->whereNumber('id')->name('resource.destroy');
});
