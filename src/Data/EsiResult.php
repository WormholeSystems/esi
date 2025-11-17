<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Data;

use Illuminate\Http\Client\Response;
use Throwable;
use WormholeSystems\ESI\Data\Errors\AuthenticationError;
use WormholeSystems\ESI\Data\Errors\AuthorizationError;
use WormholeSystems\ESI\Data\Errors\BadRequestError;
use WormholeSystems\ESI\Data\Errors\ErrorLimitExceededError;
use WormholeSystems\ESI\Data\Errors\EsiConnectionError;
use WormholeSystems\ESI\Data\Errors\InvalidResponseError;
use WormholeSystems\ESI\Data\Errors\NotFoundError;
use WormholeSystems\ESI\Data\Errors\RateLimitError;
use WormholeSystems\ESI\Data\Errors\ServerError;

/**
 * @template T
 *
 * @property-read T|null $data+
 * @property-read ESIError|null $error
 */
final class EsiResult
{
    /**
     * Create a new ESI result.
     *
     * @param  T|null  $data
     */
    public function __construct(
        public readonly mixed $data = null,
        public readonly ?ESIError $error = null,
    ) {}

    public static function authenticationError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(AuthenticationError::class, $response, $e);
    }

    public static function authorizationError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(AuthorizationError::class, $response, $e);
    }

    public static function badRequestError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(BadRequestError::class, $response, $e);
    }

    public static function notFoundError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(NotFoundError::class, $response, $e);
    }

    public static function rateLimitError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(RateLimitError::class, $response, $e);
    }

    public static function serverError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(ServerError::class, $response, $e);
    }

    public static function invalidResponseError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(InvalidResponseError::class, $response, $e);
    }

    public static function esiConnectionError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(EsiConnectionError::class, $response, $e);
    }

    public static function errorLimitExceededError(?Response $response = null, ?Throwable $e = null): self
    {
        return self::fromError(ErrorLimitExceededError::class, $response, $e);
    }

    public static function success(mixed $data): self
    {
        return new self($data, null);
    }

    /**
     * Create an EsiResult from an error class.
     *
     * @param  class-string<ESIError>  $errorClass
     */
    public static function fromError(
        string $errorClass,
        ?Response $response = null,
        ?Throwable $e = null,
    ): self {
        $errorInstance = new $errorClass($response, $e);

        return new self(null, $errorInstance);
    }

    public function wasSuccessful(): bool
    {
        return $this->error === null;
    }

    public function failed(): bool
    {
        return $this->error !== null;
    }
}
