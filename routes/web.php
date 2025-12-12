<?php

use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');

// Documentation
Route::get('/docs', [DocumentationController::class, 'index'])->name('docs.index');
Route::get('/docs/{page}', [DocumentationController::class, 'show'])
    ->where('page', '.*')
    ->name('docs.show');
