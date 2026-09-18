<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/',                                'pages.home')->name('home');
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
Route::view('/events',                          'pages.events');
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
});

require __DIR__.'/auth.php';
