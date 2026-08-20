<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Menu\MenuPage;
use App\Livewire\Offers\OffersPage;
use App\Livewire\Admin\Offers\OfferList;
use App\Livewire\Admin\Offers\OfferEditor;
use App\Livewire\Admin\Settings\LoyaltyEditor;
use App\Models\TakeawayOrder;

Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SeoController::class, 'robots'])->name('robots');

Route::get('/', function (\Illuminate\Http\Request $request, \App\Services\CmsService $cms) {
    $isPreview = $request->has('preview') && auth('admin')->check();
    $version = $isPreview ? $cms->getDraftOrPublishedContent('home') : $cms->getPublishedContent('home');
    $content = $version ? ($version['content'] ?? $version->content) : [];

    $reviewsService = app(\App\Services\GoogleReviewsService::class);
    $googleReviewsEnabled = $reviewsService->enabled();
    $googleReviews = $googleReviewsEnabled ? $reviewsService->getData() : null;
    $googleReviewsUrl = $reviewsService->reviewsUrl();
    $googleReviewsWriteUrl = $reviewsService->writeReviewUrl();

    return view('pages.home', compact('content', 'googleReviewsEnabled', 'googleReviews', 'googleReviewsUrl', 'googleReviewsWriteUrl'));
})->name('home');

Route::get('/menu', MenuPage::class)->name('menu');

Route::get('/offers', OffersPage::class)->name('offers');

Route::get('/booking', \App\Livewire\Pages\BookingSelection::class)->name('booking.selection');
Route::get('/booking/table', \App\Livewire\Pages\ReservationPage::class)->name('reservation');
Route::get('/booking/venue', \App\Livewire\Venue\VenueBookingForm::class)->name('venue.booking');
Route::get('/booking/venue/{reference}', \App\Livewire\Venue\VenueBookingStatus::class)->name('venue.status');

Route::get('/our-story', \App\Livewire\Pages\OurStoryPage::class)->name('our-story');
Route::get('/our-stories/{slug}', \App\Livewire\Pages\StoryDetailPage::class)->name('our-stories.show');
Route::get('/contact', \App\Livewire\Pages\ContactPage::class)->name('contact');
Route::get('/order/{reference}', \App\Livewire\Pages\OrderStatusPage::class)->name('order.status');

// Legal & information pages
Route::get('/privacy-policy', \App\Livewire\Pages\PrivacyPolicyPage::class)->name('privacy-policy');
Route::get('/terms-and-conditions', \App\Livewire\Pages\TermsConditionsPage::class)->name('terms-and-conditions');
Route::get('/sitemap', \App\Livewire\Pages\SitemapPage::class)->name('sitemap.page');

// Admin Routes — the panel lives at config('admin.path') (see config/admin.php,
// overridable with ADMIN_PATH in .env). The legacy /admin URLs always 404.
$adminPath = config('admin.path');

if ($adminPath !== 'admin') {
    // Legacy /admin URLs must return 404 without redirecting so the panel's
    // real location is never disclosed.
    Route::any('admin', function () {
        abort(404);
    });
    Route::any('admin/{any}', function () {
        abort(404);
    })->where('any', '.*');
}

Route::prefix($adminPath)->name('admin.')->group(function () {
    // Guest Admin
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', \App\Livewire\Admin\Auth\Login::class)->name('login');
    });

    // Authenticated Admin
    Route::middleware([
        \App\Http\Middleware\EnsureAuthenticatedAdmin::class,
        \App\Http\Middleware\AdminNoCache::class,
    ])->group(function () {
        Route::get('/', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/security', \App\Livewire\Admin\Profile\Security::class)->name('security');
        Route::get('/notifications', \App\Livewire\Admin\Notifications\Index::class)->name('notifications');
        Route::get('/audit-logs', \App\Livewire\Admin\AuditLogs\Index::class)->name('audit-logs');
        Route::get('/settings', \App\Livewire\Admin\Settings\Index::class)->name('settings');
        Route::get('/settings/analytics', \App\Livewire\Admin\Settings\Analytics::class)->name('settings.analytics');
        Route::get('/settings/legal', \App\Livewire\Admin\Settings\Legal::class)->name('settings.legal');
        
        // Website CMS
        Route::get('/website/home', \App\Livewire\Admin\Website\HomeEditor::class)->name('website.home');
        // Route::get('/website/media', \App\Livewire\Admin\Website\MediaLibrary::class)->name('website.media');

        // SEO Panel
        Route::prefix('seo')->name('seo.')->group(function () {
            Route::get('/global', \App\Livewire\Admin\Seo\GlobalSettings::class)->name('global');
            Route::get('/pages', \App\Livewire\Admin\Seo\PageSettings::class)->name('pages');
            Route::get('/redirects', \App\Livewire\Admin\Seo\Redirects::class)->name('redirects');
            Route::get('/health', \App\Livewire\Admin\Seo\Health::class)->name('health');
        });

        // Menu CMS
        Route::get('/menu', \App\Livewire\Admin\Menu\Index::class)->name('menu');
        Route::get('/menu/category/create', \App\Livewire\Admin\Menu\CategoryEditor::class)->name('menu.category.create');
        Route::get('/menu/category/{category}/edit', \App\Livewire\Admin\Menu\CategoryEditor::class)->name('menu.category.edit');
        Route::get('/menu/item/create', \App\Livewire\Admin\Menu\ItemEditor::class)->name('menu.item.create');
        Route::get('/menu/item/{item}/edit', \App\Livewire\Admin\Menu\ItemEditor::class)->name('menu.item.edit');
        

        // Offers (Our Deals) CMS
        Route::get('/offers', \App\Livewire\Admin\Offers\OfferList::class)->name('offers');
        Route::get('/offers/create', \App\Livewire\Admin\Offers\OfferEditor::class)->name('offers.create');
        Route::get('/offers/{offer}/edit', \App\Livewire\Admin\Offers\OfferEditor::class)->name('offers.edit');
        Route::get('/website/rewards', \App\Livewire\Admin\Settings\LoyaltyEditor::class)->name('loyalty');

        // Stories CMS
        Route::get('/stories', \App\Livewire\Admin\Stories\Index::class)->name('stories');
        Route::get('/stories/create', \App\Livewire\Admin\Stories\StoryEditor::class)->name('stories.create');
        Route::get('/stories/{story}/edit', \App\Livewire\Admin\Stories\StoryEditor::class)->name('stories.edit');

        // Operations Center
        Route::get('/operations', \App\Livewire\Admin\Operations\Index::class)->name('operations');

        // Takeaway Orders
        Route::get('/orders', \App\Livewire\Admin\Orders\Index::class)->name('orders');
        Route::get('/orders/{reference}', \App\Livewire\Admin\Orders\Show::class)->name('orders.show');

        // Unified Bookings
        Route::get('/bookings', \App\Livewire\Admin\Bookings\Index::class)->name('bookings.index');
        Route::get('/bookings/{reference}', \App\Livewire\Admin\Bookings\Show::class)->name('bookings.show');

        // Tables
        Route::get('/tables', \App\Livewire\Admin\Tables\Index::class)->name('tables');


        // Contact Messages
        Route::get('/contact', \App\Livewire\Admin\Contact\Index::class)->name('contact.messages');
        Route::get('/contact/{id}', \App\Livewire\Admin\Contact\Show::class)->name('contact.show');

        // Venue Management
        Route::prefix('venues')->name('venues.')->group(function () {
            Route::get('/settings', \App\Livewire\Admin\Venues\Settings::class)->name('settings');
            Route::get('/calendar', \App\Livewire\Admin\Venues\Calendar::class)->name('calendar');
        });


        // Reports
        Route::get('/reports', \App\Livewire\Admin\Reports\Index::class)->name('reports');
        
        Route::post('/logout', function (\Illuminate\Http\Request $request) {
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        })->name('logout');
    });
});

Route::get('/takeaway', function () {
    return redirect()->route('menu', ['mode' => 'takeaway']);
})->name('takeaway');

Route::get('/takeaway/confirmation/{reference}', function ($reference) {
    $order = \App\Models\TakeawayOrder::where('reference', $reference)->firstOrFail();
    return view('pages.order-confirmation', compact('order'));
})->name('takeaway.confirmation');

// Fallback Route for QR Redirects
Route::fallback(function (\Illuminate\Http\Request $request) {
    $path = '/' . ltrim($request->path(), '/');
    $redirect = \App\Models\SlugRedirect::where('old_path', $path)->first();
    
    if ($redirect) {
        return redirect($redirect->new_path, $redirect->status_code);
    }
    
    abort(404);
});
