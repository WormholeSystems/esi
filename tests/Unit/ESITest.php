<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use WormholeSystems\ESI\ESI;
use WormholeSystems\ESI\Features\AssetFeature;
use WormholeSystems\ESI\Features\CharacterFeature;

it('can instantiate the ESI class', function () {
    $esi = app(ESI::class);

    expect($esi)->toBeInstanceOf(ESI::class);
    expect($esi->characters)->toBeInstanceOf(CharacterFeature::class);
    expect($esi->assets)->toBeInstanceOf(AssetFeature::class);
});

it('can get character info through feature class', function () {
    Http::fake([
        '*/latest/characters/123456789/*' => Http::response([
            'name' => 'Test Character',
            'corporation_id' => 98765432,
        ]),
    ]);

    $esi = app(ESI::class);
    $result = $esi->characters->get(123456789);

    expect($result->isSuccess())->toBeTrue();
    expect($result->data)->toBeInstanceOf(WormholeSystems\ESI\Data\Character\CharacterInfo::class);
    expect($result->data->name)->toBe('Test Character');
});

it('handles errors through feature class', function () {
    Http::fake([
        '*/latest/characters/999/*' => Http::response([
            'error' => 'Character not found',
        ], 404),
    ]);

    $esi = app(ESI::class);
    $result = $esi->characters->get(999);

    expect($result->isFailure())->toBeTrue();
    expect($result->error)->toBeInstanceOf(WormholeSystems\ESI\Data\Errors\NotFoundError::class);
    expect($result->error->body['error'])->toBe('Character not found');
});

it('can access the underlying connector for advanced usage', function () {
    $esi = app(ESI::class);

    expect($esi->getConnector())->toBeInstanceOf(WormholeSystems\ESI\Http\Connector::class);
});
