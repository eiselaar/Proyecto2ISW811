<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use App\Services\Socialite\MastodonProvider;
use App\Services\Socialite\RedditProvider;
use Illuminate\Support\Arr;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar servicios en el contenedor
        $this->app->singleton(\App\Services\TwoFactorService::class, function ($app) {
            return new \App\Services\TwoFactorService();
        });
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Registrar componentes
        Blade::component('layouts.app', 'app-layout');
        Blade::component('components.ui.application-logo', 'application-logo');
        Blade::component('components.navigation-link', 'navigation-link');

        Blade::component('components.posts.post-card', 'post-card');

        Blade::component('components.posts.post-status', 'post-status');
        

        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('M d, Y H:i'); ?>";
        });

        // Configuración de Mastodon
        Socialite::extend('mastodon', function ($app) {
            $config = $app['config']['services.mastodon'] ?? [];

            // Asegurarnos de que tenemos un array con todas las claves necesarias
            $config = array_merge([
                'client_id' => null,
                'client_secret' => null,
                'redirect' => null,
                'instance_url' => null,
            ], is_array($config) ? $config : []);

            return new MastodonProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect'],
                $config['instance_url']
            );
        });

        // Configuración de Reddit
        Socialite::extend('reddit', function ($app) {
            $config = $app['config']['services.reddit'] ?? [];

            // Asegurarnos de que tenemos un array con todas las claves necesarias
            $config = array_merge([
                'client_id' => null,
                'client_secret' => null,
                'redirect' => null,
            ], is_array($config) ? $config : []);

            return new RedditProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }
}