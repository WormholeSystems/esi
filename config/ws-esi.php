<?php

declare(strict_types=1);

// config for NicolasKion/ESI
return [

    /*
    |--------------------------------------------------------------------------
    | ESI Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the ESI API. Use the tranquility server for live data
    | or singularity for test server data.
    |
    */
    'base_url' => env('ESI_BASE_URL', 'https://esi.evetech.net'),

    /*
    |--------------------------------------------------------------------------
    | EVE SSO Configuration
    |--------------------------------------------------------------------------
    |
    | Your EVE Online application credentials from
    | https://developers.eveonline.com/applications
    |
    */
    'client_id' => env('ESI_CLIENT_ID'),
    'client_secret' => env('ESI_CLIENT_SECRET'),
    'callback_url' => env('ESI_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Default Datasource
    |--------------------------------------------------------------------------
    |
    | The default datasource to use: tranquility or singularity
    |
    */
    'datasource' => env('ESI_DATASOURCE', 'tranquility'),

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Enable/disable automatic response caching based on ESI cache headers
    |
    */
    'cache_enabled' => env('ESI_CACHE_ENABLED', true),
    'cache_driver' => env('ESI_CACHE_DRIVER', config('cache.default')),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting behavior
    |
    */
    'rate_limit' => [
        'enabled' => true,
        'max_retries' => 3,
        'retry_delay' => 1000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | User Agent
    |--------------------------------------------------------------------------
    |
    | Custom user agent for API requests. ESI requires a contact in the UA.
    |
    */
    'user_agent' => env('ESI_USER_AGENT', 'Laravel ESI Package (your-email@example.com)'),

];
