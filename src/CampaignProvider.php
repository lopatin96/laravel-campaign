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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-campaign');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-campaign');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-campaign');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-campaign')
        ], 'laravel-campaign-views');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/laravel-campaign'),
        ], 'laravel-campaign-lang');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('/migrations')
        ], 'laravel-campaign-migrations');

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('laravel-campaign.php')
        ], 'laravel-campaign-config');
    }
}