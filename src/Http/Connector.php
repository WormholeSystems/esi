<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Http;

use Exception;
use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Throwable;
use WormholeSystems\ESI\Auth\Token;
use WormholeSystems\ESI\Contracts\HasPaginationContract;
use WormholeSystems\ESI\Data\Errors\EsiConnectionError;
use WormholeSystems\ESI\Data\Errors\InvalidResponseError;
use WormholeSystems\ESI\Data\EsiResult;

use function count;
use function mb_trim;
use function sprintf;

final readonly class Connector
{
    private const string COMPATIBILITY_DATE = '2025-11-06';

    public function __construct(
        private Factory $client,
        #[Config('esi.base_url', 'https://esi.evetech.net')] private string $baseUrl,
        #[Config('esi.user_agent')] private string $userAgent
    ) {}

    /**
     * Send a request and return the response.
     */
    public function send(Request|HasPaginationContract $request, ?Token $token = null): EsiResult
    {
        // Todo: Check if the token is still valid before sending the request

        if ($request instanceof HasPaginationContract) {
            return $this->sendPaginatedRequest($request, $token);
        }

        return $this->sendRequest($request, $token);
    }

    private function getFullUrl(string $url): string
    {
        return sprintf(
            '%s/%s',
            mb_trim($this->baseUrl, '/'),
            mb_trim($url)
        );
    }

    private function sendRequest(Request $request, ?Token $token = null): EsiResult
    {
        // Get request details
        $url = $this->getFullUrl($request->path());
        $query = $request->query();
        $body = $request->body();

        try {
            $response = $this->client
                // Set common metadata headers
                ->withHeader('User-Agent', $this->userAgent)
                ->withHeader('X-Compatibility-Date', self::COMPATIBILITY_DATE)
                // Apply conditional parameters
                ->when(count($query), fn (PendingRequest $request) => $request->withQueryParameters($query))
                ->when($body, fn (PendingRequest $request) => $request->withBody($body))
                ->when($token, fn (PendingRequest $request) => $request->withToken($token->getAccessToken()))
                // Configure retry logic
                ->retry(
                    times: $request->maxRetries,
                    sleepMilliseconds: $request->retryAfterSeconds * 1_000,
                    when: fn (Response $response) => $request->shouldRetry($response),
                    throw: false
                )->send($request->method->value, $url);
        } catch (ConnectionException|Exception $e) {
            return $this->handleConnectionException($e);
        }

        try {
            return $this->handleResponse($request, $response);
        } catch (Throwable $e) {
            return new EsiResult(
                data: null,
                error: new InvalidResponseError(error: $e, response: $response)
            );
        }
    }

    private function handleConnectionException(ConnectionException $e): EsiResult
    {
        return new EsiResult(
            data: null,
            error: new EsiConnectionError(error: $e),
        );
    }

    private function handleResponse(Request $request, Response $response): EsiResult
    {
        if ($response->successful()) {
            try {
                $data = $request->toDTO($response);

                return new EsiResult(data: $data, error: null);
            } catch (Throwable $e) {
                return new EsiResult(
                    data: null,
                    error: new InvalidResponseError(error: $e, response: $response)
                );
            }
        }

        return match ($response->status()) {
            401 => EsiResult::authenticationError($response),
            403 => EsiResult::authorizationError($response),
            400 => EsiResult::badRequestError($response),
            404 => EsiResult::notFoundError($response),
            420,429 => EsiResult::rateLimitError($response),
            500, 502, 503, 504 => EsiResult::serverError($response),
            520 => EsiResult::errorLimitExceededError($response),
        };
    }
}
