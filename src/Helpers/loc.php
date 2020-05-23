<?php

if (!function_exists('loc')) {
    /**
     * @return \Koddea\Localize\Localize
     */
    function loc()
    {
        return app('localize');
    }
}
