<?php

namespace NMFCODES\GoogleMapPlaceDetail;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;

class GoogleMapPlaceDetailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__.'/../config/google-map.php');

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('google-map.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('google-map');
        }

        $this->mergeConfigFrom($source, 'google-map');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GoogleMapPlaceDetail::class, function () {
            return new GoogleMapPlaceDetail();
        });
    }
}
