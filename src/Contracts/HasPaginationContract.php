<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Contracts;

use Illuminate\Http\Client\Response;

interface HasPaginationContract
{
    public function getNextPageUrl(Response $response): ?string;
}
