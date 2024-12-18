<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicController;


Route::get('/', [MusicController::class, 'index'])->name('dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
});

Route::get('/music/random', [MusicController::class, 'random'])->name('music.random');
Route::get('/api/tracks/search', [MusicController::class, 'search']);