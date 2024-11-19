<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as AppServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\PostPublished::class => [
            \App\Listeners\SendPostPublishedNotification::class,
        ],
        \App\Events\PostScheduled::class => [
            \App\Listeners\SendPostScheduledNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
