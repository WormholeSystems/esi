<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Contracts;

use Carbon\CarbonImmutable;

interface TokenContract
{
    /**
     * Get the access token.
     */
    public function accessToken(): string;

    /**
     * Get the refresh token.
     */
    public function refreshToken(): ?string;

    /**
     * Get the token type.
     */
    public function tokenType(): string;

    /**
     * Get the expiry time as a Unix timestamp.
     */
    public function expiresAt(): CarbonImmutable;

    /**
     * Check if the token is expired.
     */
    public function isExpired(): bool;

    /**
     * Delete the token.
     */
    public function delete(): void;
}
