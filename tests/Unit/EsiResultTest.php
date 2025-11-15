<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use WormholeSystems\ESI\Data\ESIError;
use WormholeSystems\ESI\Data\EsiResult;
use WormholeSystems\ESI\Http\Connector;
use WormholeSystems\ESI\Requests\Character\GetCharacterInfo;

it('can create a successful result', function () {
    $result = EsiResult::success(['name' => 'Test Character']);

    expect($result->isSuccess())->toBeTrue();
    expect($result->isFailure())->toBeFalse();
    expect($result->data)->toBe(['name' => 'Test Character']);
    expect($result->error)->toBeNull();
});

it('can create a failed result', function () {
    $error = new ESIError(
        body: ['error' => 'Not Found'],
        statusCode: 404
    );

    $result = EsiResult::failure($error);

    expect($result->isSuccess())->toBeFalse();
    expect($result->isFailure())->toBeTrue();
    expect($result->error)->toBe($error);
    expect($result->data)->toBeNull();
});

it('can unwrap successful result', function () {
    $result = EsiResult::success('data');

    expect($result->unwrap())->toBe('data');
});

it('throws when unwrapping failed result', function () {
    $error = new ESIError(
        body: ['error' => 'Failed'],
        statusCode: 500
    );
    $result = EsiResult::failure($error);

    $result->unwrap();
})->throws(RuntimeException::class);

it('can unwrap with default value', function () {
    $success = EsiResult::success('data');
    $failure = EsiResult::failure(new ESIError(['error' => 'Failed'], 500));

    expect($success->unwrapOr('default'))->toBe('data');
    expect($failure->unwrapOr('default'))->toBe('default');
});

it('can map successful result', function () {
    $result = EsiResult::success(5);
    $mapped = $result->map(fn ($x) => $x * 2);

    expect($mapped->isSuccess())->toBeTrue();
    expect($mapped->data)->toBe(10);
});

it('does not map failed result', function () {
    $error = new ESIError(['error' => 'Failed'], 500);
    $result = EsiResult::failure($error);
    $mapped = $result->map(fn ($x) => $x * 2);

    expect($mapped->isFailure())->toBeTrue();
    expect($mapped->error)->toBe($error);
});

it('can execute callback on success', function () {
    $called = false;
    $result = EsiResult::success('data');

    $result->onSuccess(function ($data) use (&$called) {
        $called = true;
        expect($data)->toBe('data');
    });

    expect($called)->toBeTrue();
});

it('does not execute success callback on failure', function () {
    $called = false;
    $error = new ESIError(['error' => 'Failed'], 500);
    $result = EsiResult::failure($error);

    $result->onSuccess(function () use (&$called) {
        $called = true;
    });

    expect($called)->toBeFalse();
});

it('can execute callback on failure', function () {
    $called = false;
    $error = new ESIError(['error' => 'Failed'], 500);
    $result = EsiResult::failure($error);

    $result->onFailure(function ($err) use (&$called, $error) {
        $called = true;
        expect($err)->toBe($error);
    });

    expect($called)->toBeTrue();
});

it('integrates with connector', function () {
    Http::fake([
        '*/latest/characters/123456789/*' => Http::response([
            'name' => 'Test Character',
            'corporation_id' => 98765432,
        ]),
    ]);

    $connector = app(Connector::class);
    $request = new GetCharacterInfo(123456789);
    $result = $connector->sendAsResult($request);

    expect($result->isSuccess())->toBeTrue();
    expect($result->data->name)->toBe('Test Character');
});

it('handles errors with connector', function () {
    Http::fake([
        '*/latest/characters/999/*' => Http::response([
            'error' => 'Character not found',
        ], 404),
    ]);

    $connector = app(Connector::class);
    $request = new GetCharacterInfo(999);
    $result = $connector->sendAsResult($request);

    expect($result->isFailure())->toBeTrue();
    expect($result->error->body['error'])->toBe('Character not found');
    expect($result->error->statusCode)->toBe(404);
});
