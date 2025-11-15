<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Contracts;

use Illuminate\Http\Client\Response;
use WormholeSystems\ESI\Enums\RequestMethod;

interface RequestContract
{
    /**
     * Get the HTTP method for this request.
     */
    public RequestMethod $method { get; }

    /**
     * How many seconds to wait between retries.
     */
    public int $retryAfterSeconds { get; }

    /**
     * How many times to retry the request on failure.
     */
    public int $maxRetries { get; }

    /**
     * Get the path for this request.
     */
    public function path(): string;

    /**
     * Get the request body.
     */
    public function body(): mixed;

    /**
     * Get the query parameters.
     *
     * @return array<string, mixed>
     */
    public function query(): array;

    /**
     * Convert the response to a DTO.
     */
    public function toDTO(Response $response): mixed;

    /**
     * Determine if the request should be retried based on the response.
     */
    public function shouldRetry(Response $response): bool;
}
