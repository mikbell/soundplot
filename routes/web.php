<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\YouTubeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('samples', SampleController::class);

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/samples/{sample}/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/samples/{sample}/wishlist', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::get('/youtube/search', [YouTubeController::class, 'search'])->name('youtube.search');
    Route::get('/youtube/random', [YouTubeController::class, 'random'])->name('youtube.random');
    Route::get('/youtube/favorites', [YouTubeController::class, 'favorites'])->name('youtube.favorites');

    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{video_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

});
