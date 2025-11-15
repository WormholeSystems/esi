<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Data;

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

    public function wasSuccessful(): bool
    {
        return $this->error === null;
    }

    public function failed(): bool
    {
        return $this->error !== null;
    }
}
