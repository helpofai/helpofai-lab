<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('pens', PenController::class)->except(['create']);
        Route::get('editor/create', [PenController::class, 'createEditor'])->name('editor.create');
    });
});
