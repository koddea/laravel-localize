<?php

namespace Koddea\Localize\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Koddea\Localize\Localize
 */
class Loc extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'localize';
    }
}
