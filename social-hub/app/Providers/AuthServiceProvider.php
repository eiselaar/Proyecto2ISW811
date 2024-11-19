<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as AppServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\Schedule;
use App\Policies\PostPolicy;
use App\Policies\SchedulePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
        Schedule::class => SchedulePolicy::class,
    ];

    public function boot(): void
    {
        // Registrar cualquier política de autorización
        Gate::define('manage-social-accounts', function ($user) {
            return true; // Todos los usuarios autenticados pueden gestionar sus cuentas sociales
        });

        Gate::define('view-analytics', function ($user) {
            return true; // Todos los usuarios pueden ver sus analíticas
        });
    }
}
