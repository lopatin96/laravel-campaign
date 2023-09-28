<?php

namespace Atin\LaravelCampaign;

use Illuminate\Support\ServiceProvider;

class CampaignProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-campaign');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('/migrations')
        ], 'laravel-campaign-migrations');

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('laravel-campaign.php')
        ], 'laravel-campaign-config');
    }
}