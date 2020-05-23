<?php

if (!function_exists('locale')) {
    /**
     * @return null|string
     */
    function locale($locale = null)
    {
        $loc = app('localize');
        if ($locale) {
            return $loc->setLocale($locale);
        }

        return $loc->getLocale();
    }
}
