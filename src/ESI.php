<?php

declare(strict_types=1);

namespace WormholeSystems\ESI;

use WormholeSystems\ESI\Features\AssetFeature;
use WormholeSystems\ESI\Features\CharacterFeature;
use WormholeSystems\ESI\Http\Connector;

final readonly class ESI
{
    public CharacterFeature $characters;

    public AssetFeature $assets;

    public function __construct(
        private Connector $connector
    ) {
        $this->characters = new CharacterFeature($this->connector);
        $this->assets = new AssetFeature($this->connector);
    }

    /**
     * Get the underlying connector for advanced usage.
     */
    public function getConnector(): Connector
    {
        return $this->connector;
    }
}
