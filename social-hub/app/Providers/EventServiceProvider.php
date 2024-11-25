<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
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
        SocialiteWasCalled::class => [
            'SocialiteProviders\\LinkedIn\\LinkedInExtendSocialite@handle',
            'SocialiteProviders\\Mastodon\\MastodonExtendSocialite@handle',
            'SocialiteProviders\\Reddit\\RedditExtendSocialite@handle',
        ],
    ];
    
}
