<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Requests\Character;

use Illuminate\Http\Client\Response;
use WormholeSystems\ESI\Data\Character\CharacterInfo;
use WormholeSystems\ESI\Http\Request;

final class GetCharacterInfo extends Request
{
    /**
     * Create a new request instance.
     */
    public function __construct(
        protected int $characterId
    ) {}

    /**
     * Get the path for this request.
     */
    public function path(): string
    {
        return "characters/$this->characterId/";
    }

    /**
     * Convert the response to a DTO.
     */
    public function toDTO(Response $response): CharacterInfo
    {
        return CharacterInfo::fromArray($response->json());
    }
}
