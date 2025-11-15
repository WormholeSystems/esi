<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Http;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Throwable;
use WormholeSystems\ESI\Auth\Token;
use WormholeSystems\ESI\Contracts\HasPaginationContract;
use WormholeSystems\ESI\Data\Errors\EsiConnectionError;
use WormholeSystems\ESI\Data\Errors\InvalidResponseError;
use WormholeSystems\ESI\Data\EsiResult;
use WormholeSystems\ESI\Enums\RequestMethod;

use function count;
use function mb_trim;
use function sprintf;

final readonly class Connector
{
    private const string COMPATIBILITY_DATE = '2025-11-06';

    private string $baseUrl;

    private string $datasource;

    private string $userAgent;

    public function __construct(
        private Factory $client,
    ) {
        $this->baseUrl = config('esi.base_url', 'https://esi.evetech.net');
        $this->datasource = config('esi.datasource', 'tranquility');
        $this->userAgent = config('esi.user_agent', 'Laravel ESI Package');
    }

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
        // Set common metadata headers
        $this->client->withHeader('User-Agent', $this->userAgent);
        $this->client->withHeader('X-Compatibility-Date', self::COMPATIBILITY_DATE);

        // Get request details
        $url = $this->getFullUrl($request->path());
        $query = $request->query();
        $body = $request->body();

        // Apply conditional parameters
        $this->client->when(count($query), fn () => $this->client->withQueryParameters($query));
        $this->client->when($body, fn () => $this->client->withBody($body));
        $this->client->when($token, fn () => $this->client->withToken($token->getAccessToken()));

        // Configure retry logic
        $this->client->retry(
            times: $request->maxRetries,
            sleepMilliseconds: $request->retryAfterSeconds * 1_000,
            when: fn (Response $response) => $request->shouldRetry($response)
        );

        try {
            $response = match ($request->method) {
                RequestMethod::GET => $this->client->get($url),
                RequestMethod::POST => $this->client->post($url),
                RequestMethod::PUT => $this->client->put($url),
                RequestMethod::DELETE => $this->client->delete($url),
                RequestMethod::PATCH => $this->client->patch($url),
            };
        } catch (ConnectionException $e) {
            return $this->handleConnectionException($e);
        }

        try {
            return $request->toDTO($response);
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
}
