<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Data\Character;

use WormholeSystems\ESI\Data\DTO;

final class CharacterInfo extends DTO
{
    /**
     * Create a new character info DTO.
     */
    public function __construct(
        public readonly int $characterId,
        public readonly string $name,
        public readonly int $corporationId,
        public readonly ?int $allianceId,
        public readonly ?int $ancestryId,
        public readonly ?string $birthday,
        public readonly ?string $description,
        public readonly int $raceId,
        public readonly ?int $bloodlineId,
        public readonly ?int $factionId,
        public readonly string $gender,
        public readonly ?string $securityStatus,
        public readonly ?string $title,
    ) {}

    /**
     * Create a DTO from an array of data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            characterId: $data['character_id'] ?? 0,
            name: $data['name'] ?? '',
            corporationId: $data['corporation_id'] ?? 0,
            allianceId: $data['alliance_id'] ?? null,
            ancestryId: $data['ancestry_id'] ?? null,
            birthday: $data['birthday'] ?? null,
            description: $data['description'] ?? null,
            raceId: $data['race_id'] ?? 0,
            bloodlineId: $data['bloodline_id'] ?? null,
            factionId: $data['faction_id'] ?? null,
            gender: $data['gender'] ?? '',
            securityStatus: $data['security_status'] ?? null,
            title: $data['title'] ?? null,
        );
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'character_id' => $this->characterId,
            'name' => $this->name,
            'corporation_id' => $this->corporationId,
            'alliance_id' => $this->allianceId,
            'ancestry_id' => $this->ancestryId,
            'birthday' => $this->birthday,
            'description' => $this->description,
            'race_id' => $this->raceId,
            'bloodline_id' => $this->bloodlineId,
            'faction_id' => $this->factionId,
            'gender' => $this->gender,
            'security_status' => $this->securityStatus,
            'title' => $this->title,
        ];
    }
}
