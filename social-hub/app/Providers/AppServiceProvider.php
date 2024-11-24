<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;

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
        
          
    Blade::component('layout.app', AppLayout::class);
        // Registrar directivas Blade personalizadas
        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('M d, Y H:i'); ?>";
        });
    }
}
