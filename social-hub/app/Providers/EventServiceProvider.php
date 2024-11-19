<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as AppServiceProvider;

class EventServiceProvider extends AppServiceProvider
{

    protected $listen = [
        \App\Events\PostPublished::class => [
            \App\Listeners\SendPostPublishedNotification::class,
        ],
        \App\Events\PostScheduled::class => [
            \App\Listeners\SendPostScheduledNotification::class,
        ],
    ];
    
}
