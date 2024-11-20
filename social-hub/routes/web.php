<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Rutas de 2FA
    Route::prefix('2fa')->group(function () {
        Route::get('enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
        Route::post('enable', [TwoFactorController::class, 'store'])->name('2fa.store');
        Route::get('verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');
        Route::post('verify', [TwoFactorController::class, 'verify2fa'])->name('2fa.verify');
    });

    // Rutas protegidas por 2FA si está habilitado
    Route::middleware(['2fa'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

        // Rutas de posts
        Route::resource('posts', PostController::class);
        Route::get('queue', [PostController::class, 'queue'])->name('posts.queue');
        Route::post('posts/{post}/cancel', [PostController::class, 'cancel'])->name('posts.cancel');

        // Rutas de programación
        Route::resource('schedules', ScheduleController::class);
        Route::post('schedules/{schedule}/toggle', [ScheduleController::class, 'toggle'])
            ->name('schedules.toggle');

        // Rutas de redes sociales
        Route::prefix('social')->group(function () {
            Route::get('accounts', [SocialController::class, 'index'])->name('social.accounts');
            Route::get('{provider}/redirect', [SocialController::class, 'redirect'])->name('social.redirect');
            Route::get('{provider}/callback', [SocialController::class, 'callback'])->name('social.callback');
            Route::delete('{provider}/disconnect', [SocialController::class, 'disconnect'])->name('social.disconnect');
        });

        // Rutas de notificaciones
        Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.mark-read');
    });
});