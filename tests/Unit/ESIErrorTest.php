<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use WormholeSystems\ESI\Data\ESIError;
use WormholeSystems\ESI\Http\Connector;
use WormholeSystems\ESI\Requests\Character\GetCharacterInfo;

it('creates error from response', function () {
    Http::fake(function ($request) {
        if (str_contains($request->url(), 'characters/123456789')) {
            return Http::response([
                'error' => 'Rate limit exceeded',
                'timeout' => 60,
            ], 429, ['X-ESI-Error-Limit-Reset' => '60']);
        }

        return Http::response(['error' => 'Unknown'], 500);
    });

    $connector = app(Connector::class);
    $request = new GetCharacterInfo(123456789);
    $response = $connector->send($request);

    expect($response->failed())->toBeTrue();
    expect($response->status())->toBe(429);

    $error = ESIError::fromResponse($response->getLaravelResponse());
    expect($error->body['error'])->toBe('Rate limit exceeded');
    expect($error->body['timeout'])->toBe(60);
    expect($error->statusCode)->toBe(429);
});

it('returns failed response without throwing', function () {
    Http::fake(function ($request) {
        if (str_contains($request->url(), 'characters/999999999')) {
            return Http::response([
                'error' => 'Character not found',
                'error_code' => 1404,
            ], 404);
        }

        return Http::response(['error' => 'Unknown'], 500);
    });

    $connector = app(Connector::class);
    $request = new GetCharacterInfo(999999999);
    $response = $connector->send($request);

    expect($response->failed())->toBeTrue();
    expect($response->status())->toBe(404);

    $error = ESIError::fromResponse($response->getLaravelResponse());
    expect($error->body['error'])->toBe('Character not found');
    expect($error->body['error_code'])->toBe(1404);
    expect($error->statusCode)->toBe(404);
});

it('extracts timeout from headers', function () {
    Http::fake(fn () => Http::response(
        ['error' => 'Rate limit'],
        429,
        ['X-ESI-Error-Limit-Reset' => '120']
    ));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error->body['timeout'])->toBe(120);
});

it('adds default error message if not present', function () {
    Http::fake(fn () => Http::response([], 404));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error->body['error'])->toBe('Not Found');
    expect($error->statusCode)->toBe(404);
});
