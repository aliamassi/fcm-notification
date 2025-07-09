<?php

namespace AliAmassi\FcmNotification;

use Illuminate\Support\ServiceProvider;

class FirebaseNotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__ . '/config/fcm.php', 'fcm');

        // Bind client
        $this->app->singleton(FirebaseClient::class, function($app) {
            return new FirebaseClient(
                config('fcm.credentials'),
                config('fcm.project_id')
            );
        });

        // Facade alias
        $this->app->alias(FirebaseClient::class, 'fcm.notification');
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/config/fcm.php' => config_path('fcm.php'),
        ], 'config');
    }
}
