<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \WormholeSystems\ESI\ESI
 */
final class ESI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \WormholeSystems\ESI\ESI::class;
    }
}
