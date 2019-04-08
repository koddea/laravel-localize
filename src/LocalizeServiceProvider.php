<?php

namespace Koddea\Localize;

use Illuminate\Support\ServiceProvider;

class LocalizeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/localize.php', 'localize');

        $this->app->singleton('localize', function ()  {
            return new Localize($this->app);
        });

        $this->registerHelpers();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/localize.php' => config_path('localize.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/localize'),
        ], 'views');

        $this->publishes([
            __DIR__.'/Migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'localize');

    }

    public function registerHelpers()
    {
        require_once __DIR__ . '/Helpers/loc.php';
        require_once __DIR__ . '/Helpers/locale.php';
        require_once __DIR__ . '/Helpers/locales.php';
    }

}
