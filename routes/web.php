<?php

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\MediaUploadController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SeoGeneratorController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredEvents = \App\Models\Event::published()
        ->orderByRaw('event_date IS NULL, ABS(TIMESTAMPDIFF(DAY, event_date, NOW()))')
        ->limit(3)
        ->get();

    return view('pages.home', compact('featuredEvents'));
})->name('home');
Route::view('/about',                           'pages.about')->name('about');
Route::view('/services',                        'pages.services.index')->name('services');
Route::view('/services/planning-coordination',  'pages.services.planning-coordination');
Route::view('/services/conference-planning',    'pages.services.conference-planning');
Route::view('/services/delegation-management',  'pages.services.delegation-management');
Route::view('/services/events-productions',     'pages.services.events-productions');
Route::view('/services/security-coordination',  'pages.services.security-coordination');
Route::view('/services/media-coverage',         'pages.services.media-coverage');
Route::view('/services/travel-experiences',     'pages.services.travel-experiences');
Route::view('/management-training',             'pages.management-training');
Route::view('/products',                        'pages.products');
Route::get('/events',                           [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}',              [EventController::class, 'show'])->name('events.show');
Route::view('/insights',                        'pages.insights');
Route::view('/contact',                         'pages.contact')->name('contact');
Route::view('/legal-notice',                    'pages.legal.notice');
Route::view('/privacy-policy',                  'pages.legal.privacy');
Route::view('/cookie-policy',                   'pages.legal.cookie');
Route::view('/terms-conditions',                'pages.legal.terms');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('events/generate-seo', [SeoGeneratorController::class, 'seo'])->name('events.generate-seo');
        Route::post('events/generate-description', [SeoGeneratorController::class, 'description'])->name('events.generate-description');
        Route::post('media/upload', MediaUploadController::class)->name('media.upload');
        Route::resource('events', AdminEventController::class)->except(['show']);

        Route::get   ('sections/templates',      [SectionController::class, 'templates'])->name('sections.templates');
        Route::post  ('sections',                [SectionController::class, 'store'])->name('sections.store');
        Route::post  ('sections/reorder',        [SectionController::class, 'reorder'])->name('sections.reorder');
        Route::get   ('sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
        Route::patch ('sections/{section}',      [SectionController::class, 'update'])->name('sections.update');
        Route::delete('sections/{section}',      [SectionController::class, 'destroy'])->name('sections.destroy');
    });
});

require __DIR__.'/auth.php';
