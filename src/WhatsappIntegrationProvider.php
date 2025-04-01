<?php

namespace Eglobal\WhatsappIntegration;

use Illuminate\Support\ServiceProvider;

class WhatsappIntegrationProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__ . '/config/whatsapp-integration.php' => config_path('whatsapp-integration.php'),
        ], 'eglobal-whatsapp-config');
    }

    public function register() {}
}
