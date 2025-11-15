<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use WormholeSystems\ESI\Auth\Token;
use WormholeSystems\ESI\Http\Connector;
use WormholeSystems\ESI\Requests\Character\GetCharacterInfo;

it('can send a request and get a response', function () {
    // Fake all HTTP responses
    Http::fake(function ($request) {
        if (str_contains($request->url(), 'characters/123456789')) {
            return Http::response([
                'character_id' => 123456789,
                'name' => 'Test Character',
                'corporation_id' => 98765432,
                'birthday' => '2015-03-24T11:37:00Z',
                'race_id' => 1,
                'gender' => 'male',
            ], 200);
        }

        return Http::response(['error' => 'Not Found'], 404);
    });

    // Create connector
    $connector = app(Connector::class);

    // Send request
    $request = new GetCharacterInfo(123456789);
    $response = $connector->send($request);

    // Assert response
    expect($response->successful())->toBeTrue();
    expect($response->status())->toBe(200);

    // Convert to DTO
    $dto = $request->toDTO($response);
    expect($dto->characterId)->toBe(123456789);
    expect($dto->name)->toBe('Test Character');
    expect($dto->corporationId)->toBe(98765432);
});

it('can send an authenticated request with a token', function () {
    // Fake all HTTP responses
    Http::fake(function ($request) {
        if (str_contains($request->url(), 'characters/123456789')) {
            return Http::response([
                'character_id' => 123456789,
                'name' => 'Test Character',
                'corporation_id' => 98765432,
                'birthday' => '2015-03-24T11:37:00Z',
                'race_id' => 1,
                'gender' => 'male',
            ], 200);
        }

        return Http::response(['error' => 'Not Found'], 404);
    });

    // Create token
    $token = Token::fromArray([
        'access_token' => 'test_access_token',
        'refresh_token' => 'test_refresh_token',
        'token_type' => 'Bearer',
        'expires_in' => 1200,
    ]);

    // Create connector with token
    $connector = app(Connector::class)->withToken($token);

    // Send request
    $request = new GetCharacterInfo(123456789);
    $response = $connector->send($request);

    // Assert response
    expect($response->successful())->toBeTrue();
    expect($connector->getToken())->toBe($token);

    // Verify the request was sent with authorization
    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization') &&
            str_contains($request->header('Authorization')[0], 'test_access_token');
    });
});

it('can parse ESI response headers', function () {
    // Fake HTTP responses with ESI headers
    Http::fake(function ($request) {
        if (str_contains($request->url(), 'characters/123456789')) {
            return Http::response(
                ['data' => 'test'],
                200,
                [
                    'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 300),
                    'ETag' => '"abc123"',
                    'X-Pages' => '5',
                    'X-ESI-Error-Limit-Remain' => '100',
                    'X-ESI-Error-Limit-Reset' => '60',
                ]
            );
        }

        return Http::response(['error' => 'Not Found'], 404);
    });

    $connector = app(Connector::class);
    $request = new GetCharacterInfo(123456789);
    $response = $connector->send($request);

    // Assert ESI headers are parsed correctly
    expect($response->getCacheExpiry())->toBeGreaterThan(0);
    expect($response->getETag())->toBe('"abc123"');
    expect($response->getPages())->toBe(5);
    expect($response->getErrorLimitRemain())->toBe(100);
    expect($response->getErrorLimitReset())->toBe(60);
});
