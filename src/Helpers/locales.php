<?php

use Illuminate\Support\Facades\App;

if (!function_exists('_t')) {
    function _t($text, $placeholder = false, $toLocale = null)
    {
        return App::make('localize')->translate($text, $toLocale, $placeholder, null);
    }
}

if (!function_exists('locales')) {
    /**
     * @return \Illuminate\Support\Collection
     */
    function locales()
    {
        return app('localize')->getSupportedLocales();
    }
}
