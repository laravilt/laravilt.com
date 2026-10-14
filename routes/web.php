<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');

// Brand Assets
Route::get('/brand', [BrandController::class, 'index'])->name('brand');

// Demo
Route::get('/demo', [DemoController::class, 'index'])->name('demo');
Route::post('/demo/register', [DemoController::class, 'register'])->name('demo.register');
Route::post('/demo/instant', [DemoController::class, 'instant'])->name('demo.instant');
Route::post('/demo/reset', [DemoController::class, 'reset'])->name('demo.reset')->middleware('auth');
Route::post('/demo/end', [DemoController::class, 'end'])->name('demo.end')->middleware('auth');

// Sitemap & Robots
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Documentation
Route::get('/docs', [DocumentationController::class, 'index'])->name('docs.index');
Route::get('/docs/search', [DocumentationController::class, 'search'])->name('docs.search');
Route::get('/docs/{page}', [DocumentationController::class, 'show'])
    ->where('page', '.*')
    ->name('docs.show');

// Legacy starter-kit auth pages: authentication lives in the Laravilt panel.
// GET only, so Fortify's POST endpoints on the same URIs keep working.
Route::get('/login', fn () => redirect('/admin/login'))->name('login');
Route::get('/register', fn () => redirect('/admin/login'))->name('register');
Route::get('/forgot-password', fn () => redirect('/admin/login'))->name('password.request');
Route::get('/reset-password/{token?}', fn () => redirect('/admin/login'))->name('password.reset');
Route::get('/email/verify', fn () => redirect('/admin/login'))->name('verification.notice');
Route::get('/two-factor-challenge', fn () => redirect('/admin/login'))->name('two-factor.login');
Route::get('/user/confirm-password', fn () => redirect('/admin/login'))->name('password.confirm');
