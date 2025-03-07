<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Marker
    Route::get('/markers', [MarkerController::class, 'index'])->name('markers.index');
    Route::post('/markers', [MarkerController::class, 'store'])->name('markers.store');
    Route::put('/markers/{marker}', [MarkerController::class, 'update'])->name('markers.update');
    Route::delete('/markers/{marker}', [MarkerController::class, 'destroy'])->name('markers.destroy');
});

require __DIR__.'/auth.php';
