<?php

namespace MahdiAslami\Laravel\Nginx;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use MahdiAslami\Laravel\Nginx\Commands\NginxLinkCommand;
use MahdiAslami\Laravel\Nginx\Commands\NginxPublishCommand;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                NginxLinkCommand::class,
                NginxPublishCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
