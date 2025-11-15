<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Data;

abstract class DTO
{
    /**
     * Create a DTO from an array of data.
     *
     * @param  array<string, mixed>  $data
     */
    abstract public static function fromArray(array $data): self;

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /**
     * Convert the DTO to JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
