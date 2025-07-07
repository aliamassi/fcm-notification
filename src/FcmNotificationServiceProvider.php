<?php

namespace AliAmassi\FcmNotification;

use Illuminate\Support\ServiceProvider;

class FcmNotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/fcm.php', 'fcm');

        $this->app->singleton(FcmNotification::class, function ($app) {
            return new FcmNotification(config('fcm.server_key'),config('fcm.project_id'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/fcm.php' => config_path('fcm.php'),
        ], 'config');
    }
}
