<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Support\Facades
 */
class Online extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'online';
    }
}
