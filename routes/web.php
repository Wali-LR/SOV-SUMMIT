<?php

use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CommentCategoryController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\MediaUploadController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SeoGeneratorController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredEvents = \App\Models\Event::published()
        ->orderByRaw('event_date IS NULL, ABS(TIMESTAMPDIFF(DAY, event_date, NOW()))')
        ->limit(3)
        ->get();

    $latestPosts = \App\Models\Blog::published()
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->limit(3)
        ->get();

    return view('pages.home', compact('featuredEvents', 'latestPosts'));
})->name('home');
Route::view('/about',                           'pages.about')->name('about');
Route::get('/services',                         [ServiceController::class, 'index'])->name('services.index');
Route::get('/management-training',              [ServiceController::class, 'managementTraining'])->name('services.management-training');
Route::get('/services/{service:slug}',          [ServiceController::class, 'show'])->name('services.show');
Route::view('/products',                        'pages.products');
Route::get('/events',                           [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}',              [EventController::class, 'show'])->name('events.show');
Route::get('/blog',                             [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}',                 [BlogController::class, 'show'])->name('blog.show');
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
        Route::post('blogs/generate-seo', [SeoGeneratorController::class, 'blogSeo'])->name('blogs.generate-seo');
        Route::post('blogs/generate-description', [SeoGeneratorController::class, 'blogDescription'])->name('blogs.generate-description');
        Route::post('services/generate-seo', [SeoGeneratorController::class, 'serviceSeo'])->name('services.generate-seo');
        Route::post('services/generate-description', [SeoGeneratorController::class, 'serviceDescription'])->name('services.generate-description');
        Route::post('media/upload', MediaUploadController::class)->name('media.upload');
        Route::resource('events', AdminEventController::class)->except(['show']);
        Route::resource('blogs', AdminBlogController::class)->except(['show']);
        Route::resource('services', AdminServiceController::class)->except(['show']);
        Route::resource('pages', AdminPageController::class)->except(['show']);
        Route::resource('comment-categories', CommentCategoryController::class)
            ->parameters(['comment-categories' => 'comment_category'])
            ->except(['show']);

        Route::get   ('sections/templates',      [SectionController::class, 'templates'])->name('sections.templates');
        Route::post  ('sections',                [SectionController::class, 'store'])->name('sections.store');
        Route::post  ('sections/reorder',        [SectionController::class, 'reorder'])->name('sections.reorder');
        Route::get   ('sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
        Route::patch ('sections/{section}',      [SectionController::class, 'update'])->name('sections.update');
        Route::delete('sections/{section}',      [SectionController::class, 'destroy'])->name('sections.destroy');
    });
});

require __DIR__.'/auth.php';

// Dynamic page catch-all. MUST stay at the bottom so specific routes above win.
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('pages.show');
