<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Features;

use WormholeSystems\ESI\Data\EsiResult;
use WormholeSystems\ESI\Http\Connector;
use WormholeSystems\ESI\Requests\Character\GetCharacterInfo;

final readonly class CharacterFeature
{
    public function __construct(
        private Connector $connector
    ) {}

    /**
     * Get character information.
     */
    public function get(int $characterId): EsiResult
    {
        return $this->connector->sendAsResult(new GetCharacterInfo($characterId));
    }
}
