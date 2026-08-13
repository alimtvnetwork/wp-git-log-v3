<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\Contracts\LogIngestService::class, \App\Services\PdoLogIngestService::class);
        $this->app->bind(\App\Services\Contracts\ShaRegistryRepository::class, \App\Services\Database\SqliteShaRegistryRepository::class);
        $this->app->bind(\App\Services\Contracts\SplitDbWriter::class, \App\Services\Database\SqliteSplitDbWriter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
