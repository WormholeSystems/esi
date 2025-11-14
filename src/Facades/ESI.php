<?php

namespace NicolasKion\ESI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NicolasKion\ESI\ESI
 */
class ESI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \NicolasKion\ESI\ESI::class;
    }
}
