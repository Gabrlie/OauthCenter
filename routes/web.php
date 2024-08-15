<?php

use App\Http\Controllers\Auth\ClientController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/api/tokens', [ClientController::class, 'index']);
    Route::post('/oauth/tokens/revoke/{token}', [ClientController::class, 'revoke'])->name('oauth.tokens.revoke');
});

// 第三方登录
Route::group(['prefix' => 'auth'], function () {
    Route::get('/github', 'App\Http\Controllers\Auth\GitHubAuthController@redirectToProvider');
    Route::get('/github/callback', 'App\Http\Controllers\Auth\GitHubAuthController@handleProviderCallback');
});

require __DIR__.'/auth.php';
