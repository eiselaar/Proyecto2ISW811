<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use App\Events\PostPublished;
use App\Events\PostScheduled;
use App\Listeners\SendPostPublishedNotification;
use App\Listeners\SendPostScheduledNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostPublished::class => [
            SendPostPublishedNotification::class,
        ],
        
        PostScheduled::class => [
            SendPostScheduledNotification::class,
        ],
        
        SocialiteWasCalled::class => [
            'SocialiteProviders\\LinkedIn\\LinkedInExtendSocialite@handle',
            'SocialiteProviders\\Mastodon\\MastodonExtendSocialite@handle',
            'SocialiteProviders\\Reddit\\RedditExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}