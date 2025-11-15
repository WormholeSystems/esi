<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Data;

use Illuminate\Http\Client\Response;
use Throwable;

readonly class ESIError
{
    /**
     * Create a new ESI error.
     *
     * @param  array<string, mixed>  $body
     */
    public function __construct(
        public mixed $body = null,
        public ?int $statusCode = null,
        public ?Throwable $error = null,
        public ?Response $response = null,
    ) {}

    /**
     * Create an ESI error from a Laravel HTTP client response.
     */
    public static function fromResponse(Response $response): self
    {
        $body = $response->json() ?? [];
        $statusCode = $response->status();

        // Return specific error class based on status code
        return match ($statusCode) {
            401 => new Errors\AuthenticationError($body, $statusCode),
            403 => new Errors\AuthorizationError($body, $statusCode),
            404 => new Errors\NotFoundError($body, $statusCode),
            420 => new Errors\ErrorLimitExceededError($body, $statusCode),
            429 => new Errors\RateLimitError($body, $statusCode),
            400 => new Errors\BadRequestError($body, $statusCode),
            default => $statusCode >= 500
                ? new Errors\ServerError($body, $statusCode)
                : new self($body, $statusCode),
        };
    }
}
