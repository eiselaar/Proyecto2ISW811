<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;

// Rutas públicas

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Redirigir la raíz al dashboard para usuarios autenticados
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
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
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Rutas de posts
        Route::resource('posts', PostController::class);
        Route::get('queue', [PostController::class, 'queue'])->name('posts.queue');
        Route::post('posts/{post}/cancel', [PostController::class, 'cancel'])->name('posts.cancel');

        // Rutas de programación
        Route::resource('schedules', ScheduleController::class);
        Route::post('schedules/{schedule}/toggle', [ScheduleController::class, 'toggle'])
            ->name('schedules.toggle');


        Route::middleware(['auth'])->group(function () {
            Route::prefix('social')->name('social.')->group(function () {
                // Vista principal de cuentas sociales
                Route::get('accounts', [SocialController::class, 'index'])->name('accounts');

                // Conexión y autenticación
                Route::get('connect/{platform}', [SocialController::class, 'redirect'])->name('connect');

                Route::get('{platform}/callback', [SocialController::class, 'callback'])->name('callback');

                // Desconexión
                Route::delete('disconnect/{platform}', [SocialController::class, 'disconnect'])->name('disconnect');
            });
        });

        // Rutas de notificaciones
        Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.mark-read');
    });
});