<?php

namespace CommsExpress\CDN;

use CommsExpress\CDN\CDNLibrary;
use GuzzleHttp\Client;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;

class CDNServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/cdn.php' => config_path('cdn.php'),
        ], 'cdn-config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('CommsExpress\CDN\CDNLibrary', function ($app) {
            return new CDNLibrary(new Client([
                'base_uri'  =>  config('cdn.url')
            ]));
        });
    }
}