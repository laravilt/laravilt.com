<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');

// Brand Assets
Route::get('/brand', fn () => Inertia::render('Brand/Index'))->name('brand');

// Demo
Route::get('/demo', [DemoController::class, 'index'])->name('demo');
Route::post('/demo/register', [DemoController::class, 'register'])->name('demo.register');
Route::post('/demo/instant', [DemoController::class, 'instant'])->name('demo.instant');
Route::post('/demo/reset', [DemoController::class, 'reset'])->name('demo.reset')->middleware('auth');
Route::post('/demo/end', [DemoController::class, 'end'])->name('demo.end')->middleware('auth');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Documentation
Route::get('/docs', [DocumentationController::class, 'index'])->name('docs.index');
Route::get('/docs/search', [DocumentationController::class, 'search'])->name('docs.search');
Route::get('/docs/{page}', [DocumentationController::class, 'show'])
    ->where('page', '.*')
    ->name('docs.show');
