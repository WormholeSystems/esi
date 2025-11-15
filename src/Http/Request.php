<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Http;

use Illuminate\Http\Client\Response;
use WormholeSystems\ESI\Contracts\RequestContract;
use WormholeSystems\ESI\Enums\RequestMethod;

abstract class Request implements RequestContract
{
    public RequestMethod $method = RequestMethod::GET;

    public int $retryAfterSeconds = 1;

    public int $maxRetries = 5;

    abstract public function path(): string;

    public function body(): mixed
    {
        return null;
    }

    public function query(): array
    {
        return [];
    }

    /**
     * Get custom headers for this request.
     *
     * @return array<string, string>
     */
    public function headers(): array
    {
        return [];
    }

    /**
     * Determine if the request should be retried based on the response.
     */
    public function shouldRetry(Response $response): bool
    {
        return true;
    }
}
