<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ModelController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // 3D Model routes
    Route::prefix('models')->group(function () {
        Route::get('/', [ModelController::class, 'index'])->name('models.index');
        Route::get('/viewer/{id}', [ModelController::class, 'show'])->name('models.show');
        Route::post('/upload', [ModelController::class, 'store'])->name('models.store');
        Route::get('/metadata/{id}', [ModelController::class, 'getMetadata'])->name('models.metadata');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
