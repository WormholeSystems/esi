<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Features;

use RuntimeException;
use WormholeSystems\ESI\Data\EsiResult;
use WormholeSystems\ESI\Http\Connector;

final readonly class AssetFeature
{
    public function __construct(
        private Connector $connector // @phpstan-ignore property.onlyWritten
    ) {}

    /**
     * Get character assets.
     */
    public function get(int $characterId): EsiResult
    {
        // TODO: Implement GetAssetsRequest when ready
        throw new RuntimeException('Assets endpoint not yet implemented');
    }
}
