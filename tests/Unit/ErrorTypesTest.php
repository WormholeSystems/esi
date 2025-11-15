<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use WormholeSystems\ESI\Data\Errors\AuthenticationError;
use WormholeSystems\ESI\Data\Errors\AuthorizationError;
use WormholeSystems\ESI\Data\Errors\BadRequestError;
use WormholeSystems\ESI\Data\Errors\ErrorLimitExceededError;
use WormholeSystems\ESI\Data\Errors\NotFoundError;
use WormholeSystems\ESI\Data\Errors\RateLimitError;
use WormholeSystems\ESI\Data\Errors\ServerError;
use WormholeSystems\ESI\Data\ESIError;
use WormholeSystems\ESI\Http\Connector;
use WormholeSystems\ESI\Requests\Character\GetCharacterInfo;

it('creates RateLimitError for 429 responses', function () {
    Http::fake(fn () => Http::response(['error' => 'Rate limit', 'timeout' => 60], 429));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error)->toBeInstanceOf(RateLimitError::class);
    expect($error->statusCode)->toBe(429);
    expect($error->body['timeout'])->toBe(60);
});

it('creates ErrorLimitExceededError for 420 responses', function () {
    Http::fake(fn () => Http::response(['error' => 'Error limit', 'timeout' => 120], 420));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error)->toBeInstanceOf(ErrorLimitExceededError::class);
    expect($error->statusCode)->toBe(420);
    expect($error->body['timeout'])->toBe(120);
});

it('creates AuthenticationError for 401 responses', function () {
    Http::fake(fn () => Http::response(['error' => 'Unauthorized'], 401));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error)->toBeInstanceOf(AuthenticationError::class);
    expect($error->statusCode)->toBe(401);
});

it('creates AuthorizationError for 403 responses', function () {
    Http::fake(fn () => Http::response([
        'error' => 'Forbidden',
        'error_details' => ['required_scopes' => ['esi-characters.read_location.v1']],
    ], 403));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error)->toBeInstanceOf(AuthorizationError::class);
    expect($error->statusCode)->toBe(403);
    expect($error->body['error_details']['required_scopes'])->toBe(['esi-characters.read_location.v1']);
});

it('creates NotFoundError for 404 responses', function () {
    Http::fake(fn () => Http::response(['error' => 'Not found'], 404));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error)->toBeInstanceOf(NotFoundError::class);
    expect($error->statusCode)->toBe(404);
});

it('creates BadRequestError for 400 responses', function () {
    Http::fake(fn () => Http::response(['error' => 'Bad request'], 400));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error)->toBeInstanceOf(BadRequestError::class);
    expect($error->statusCode)->toBe(400);
});

it('creates ServerError for 5xx responses', function () {
    Http::fake(fn () => Http::response(['error' => 'Internal error'], 500));

    $connector = app(Connector::class);
    $response = $connector->send(new GetCharacterInfo(123));
    $error = ESIError::fromResponse($response->getLaravelResponse());

    expect($error)->toBeInstanceOf(ServerError::class);
    expect($error->statusCode)->toBe(500);
});

it('can pattern match on error types', function () {
    Http::fake(fn () => Http::response(['error' => 'Rate limit', 'timeout' => 30], 429));

    $connector = app(Connector::class);
    $result = $connector->sendAsResult(new GetCharacterInfo(123));

    expect($result->isFailure())->toBeTrue();

    $action = match (true) {
        $result->error instanceof RateLimitError => 'retry_after_'.$result->error->body['timeout'],
        $result->error instanceof AuthenticationError => 'refresh_token',
        $result->error instanceof NotFoundError => 'not_found',
        default => 'unknown',
    };

    expect($action)->toBe('retry_after_30');
});
