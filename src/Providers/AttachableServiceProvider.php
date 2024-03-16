<?php

namespace Akatekno\Attachable\Providers;

use Illuminate\Support\ServiceProvider;

class AttachableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'attachable-migrations');

        $this->loadMigrationsFrom([
            __DIR__.'/../database/migrations',
        ]);
    }
}