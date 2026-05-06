<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeAnalysisController;
use App\Http\Controllers\RecipeHistoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'    => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/recipes/upload', [RecipeAnalysisController::class, 'create'])->name('recipes.create');
    Route::post('/recipes/analyze', [RecipeAnalysisController::class, 'store'])->middleware('throttle:10,1')->name('recipes.store');
    Route::get('/recipes/{id}', [RecipeAnalysisController::class, 'show'])->name('recipes.show');
    Route::get('/api/analyses/{id}/status', [RecipeAnalysisController::class, 'status'])->name('recipes.status');

    Route::get('/history', [RecipeHistoryController::class, 'index'])->name('history');
});

require __DIR__.'/auth.php';
