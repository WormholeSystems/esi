<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Auth;

use DateTimeImmutable;

final class Token
{
    /**
     * Create a new token instance.
     */
    public function __construct(
        private string $accessToken,
        private string $refreshToken,
        private string $tokenType,
        private DateTimeImmutable $expiresAt,
        private ?int $characterId = null,
        private ?string $characterName = null,
        private ?array $scopes = null
    ) {}

    /**
     * Create a token from an array of data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $expiresAt = isset($data['expires_at'])
            ? new DateTimeImmutable($data['expires_at'])
            : (new DateTimeImmutable)->modify('+'.($data['expires_in'] ?? 1200).' seconds');

        return new self(
            accessToken: $data['access_token'],
            refreshToken: $data['refresh_token'],
            tokenType: $data['token_type'] ?? 'Bearer',
            expiresAt: $expiresAt,
            characterId: $data['character_id'] ?? null,
            characterName: $data['character_name'] ?? null,
            scopes: $data['scopes'] ?? null
        );
    }

    /**
     * Get the access token.
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Get the refresh token.
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Get the token type.
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * Get the token expiration time.
     */
    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    /**
     * Check if the token is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiresAt < new DateTimeImmutable;
    }

    /**
     * Check if the token needs refresh (expires in less than 5 minutes).
     */
    public function needsRefresh(): bool
    {
        $threshold = (new DateTimeImmutable)->modify('+5 minutes');

        return $this->expiresAt < $threshold;
    }

    /**
     * Get the character ID associated with this token.
     */
    public function getCharacterId(): ?int
    {
        return $this->characterId;
    }

    /**
     * Get the character name associated with this token.
     */
    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    /**
     * Get the scopes granted to this token.
     *
     * @return array<string>|null
     */
    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    /**
     * Check if the token has a specific scope.
     */
    public function hasScope(string $scope): bool
    {
        return $this->scopes !== null && in_array($scope, $this->scopes, true);
    }

    /**
     * Convert the token to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'token_type' => $this->tokenType,
            'expires_at' => $this->expiresAt->format('Y-m-d H:i:s'),
            'character_id' => $this->characterId,
            'character_name' => $this->characterName,
            'scopes' => $this->scopes,
        ];
    }
}
