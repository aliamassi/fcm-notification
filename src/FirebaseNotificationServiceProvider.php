<?php

namespace AliAmassi\FcmNotification;

use Illuminate\Support\ServiceProvider;

class FirebaseNotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/config/firebase.php', 'firebase');

        // Bind client
        $this->app->singleton(FirebaseClient::class, function($app) {
            return new FirebaseClient(
                config('firebase.credentials'),
                config('firebase.project_id')
            );
        });

        // Facade alias
        $this->app->alias(FirebaseClient::class, 'firebase.notification');
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/config/firebase.php' => config_path('firebase.php'),
        ], 'config');
    }
}
