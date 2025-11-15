<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Exceptions;

use Exception;
use Throwable;
use WormholeSystems\ESI\Data\ESIError;

final class ESIException extends Exception
{
    /**
     * The ESI error details.
     */
    protected ?ESIError $esiError = null;

    /**
     * Create a new ESI exception.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        ?ESIError $esiError = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->esiError = $esiError;
    }

    /**
     * Create an exception from an ESI error.
     */
    public static function fromESIError(ESIError $error, ?Throwable $previous = null): self
    {
        $message = $error->body['error'] ?? 'Unknown error';

        return new self(
            message: "{$message} (HTTP {$error->statusCode})",
            code: $error->statusCode,
            previous: $previous,
            esiError: $error
        );
    }

    /**
     * Get the ESI error details.
     */
    public function getESIError(): ?ESIError
    {
        return $this->esiError;
    }

    /**
     * Check if this exception has ESI error details.
     */
    public function hasESIError(): bool
    {
        return $this->esiError !== null;
    }
}
