# Laravel ESI Integration for EVE Online

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nicolaskion/ws-esi.svg?style=flat-square)](https://packagist.org/packages/nicolaskion/ws-esi)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nicolaskion/ws-esi/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nicolaskion/ws-esi/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nicolaskion/ws-esi/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nicolaskion/ws-esi/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nicolaskion/ws-esi.svg?style=flat-square)](https://packagist.org/packages/nicolaskion/ws-esi)

A comprehensive, type-safe Laravel package for integrating with the EVE Online ESI (EVE Swagger Interface) API. This package provides an elegant and modern way to interact with EVE Online's official API, featuring full type safety, automatic token management, and a fluent interface for all ESI endpoints.

## About EVE Online ESI

The [EVE Swagger Interface (ESI)](https://esi.evetech.net/) is the official REST API for EVE Online, CCP Games' massive multiplayer online space game. ESI provides access to a wealth of game data including:

- **Character Data**: Skills, wallet, location, assets, and more
- **Corporation & Alliance**: Member lists, structures, roles, and corporation information
- **Universe Data**: Star systems, stations, planets, and celestial objects
- **Market Data**: Market orders, prices, and trade history
- **Industry**: Manufacturing, research, and blueprints
- **Mail & Notifications**: In-game mail and notifications
- **Contacts & Standings**: Character and corporation relationships
- **Killmails**: Combat statistics and kill reports
- **And much more...**

## Goals & Features

This package aims to provide the best developer experience for working with the EVE Online ESI API in Laravel:

### 🎯 Primary Goals

- **Type Safety**: Full PHP type hints and DocBlocks for all endpoints and responses
- **Developer Experience**: Intuitive, fluent API design that feels natural in Laravel
- **Authentication**: Automatic OAuth 2.0 handling with token refresh and management
- **Rate Limiting**: Built-in rate limit handling and retry logic
- **Caching**: Smart response caching based on ESI cache headers
- **Error Handling**: Comprehensive exception handling with meaningful error messages
- **Testing**: Easy-to-use testing utilities and mocks

### ✨ Key Features

- **Complete ESI Coverage**: Support for all ESI endpoints and versions
- **OAuth 2.0 Integration**: Seamless EVE SSO authentication flow
- **Automatic Token Refresh**: No more expired token headaches
- **Response Models**: Typed response objects instead of raw arrays
- **Batch Operations**: Efficient bulk data retrieval
- **Event System**: Laravel events for API calls, errors, and token refreshes
- **Queue Integration**: Built-in support for queued API calls
- **Multi-Character Support**: Handle multiple authenticated characters
- **ESI Swagger Spec Sync**: Automatic type generation from ESI swagger spec
- **Comprehensive Documentation**: Detailed docs with examples for every endpoint

### 🚀 Why Use This Package?

- **Save Development Time**: Pre-built, tested integrations for all ESI endpoints
- **Type Safety**: Catch errors at development time, not runtime
- **Laravel Native**: Built specifically for Laravel with familiar patterns
- **Production Ready**: Rate limiting, error handling, and retry logic built-in
- **Active Maintenance**: Regular updates to match ESI changes
- **Community Driven**: Open source with contributions welcome

## Installation

You can install the package via composer:

```bash
composer require nicolaskion/ws-esi
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="ws-esi-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="ws-esi-config"
```

This is the contents of the published config file:

```php
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
    'cache_driver' => env('ESI_CACHE_DRIVER', 'redis'),

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
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="ws-esi-views"
```

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
ESI_CLIENT_ID=your_client_id
ESI_CLIENT_SECRET=your_client_secret
ESI_CALLBACK_URL=https://your-app.com/auth/callback
ESI_USER_AGENT="Your App Name (your-email@example.com)"
```

### Obtaining EVE SSO Credentials

1. Go to [EVE Developers](https://developers.eveonline.com/applications)
2. Create a new application
3. Select the scopes your application needs
4. Note your Client ID and Secret Key
5. Add your callback URL

## Usage

### Basic Public Data Access

Access public ESI endpoints without authentication:

```php
use WormholeSystems\ESI\Facades\ESI;

// Get character public information
$character = ESI::character(123456789)->info();
echo $character->name; // Character name
echo $character->corporation_id; // Corporation ID

// Get universe information
$system = ESI::universe()->system(30000142);
echo $system->name; // "Jita"
echo $system->security_status; // 0.9

// Get market data
$orders = ESI::market(10000002)->orders(); // The Forge region
foreach ($orders as $order) {
    echo "Type: {$order->type_id}, Price: {$order->price}";
}
```

### Authenticated Endpoints

For endpoints requiring authentication:

```php
use WormholeSystems\ESI\Facades\ESI;

// Redirect user to EVE SSO
return ESI::auth()->redirect(['esi-characters.read_contacts.v1']);

// In your callback route
$token = ESI::auth()->handleCallback($request);

// Use authenticated endpoints
$contacts = ESI::character($characterId)
    ->withToken($token)
    ->contacts();

// Get character location
$location = ESI::character($characterId)
    ->withToken($token)
    ->location();
    
// Get character wallet balance
$wallet = ESI::character($characterId)
    ->withToken($token)
    ->wallet();
```

### Using the Facade

```php
use WormholeSystems\ESI\Facades\ESI;

// Character endpoints
ESI::character($characterId)->info();
ESI::character($characterId)->skills();
ESI::character($characterId)->assets();

// Corporation endpoints
ESI::corporation($corpId)->info();
ESI::corporation($corpId)->members();

// Universe endpoints
ESI::universe()->systems();
ESI::universe()->types();

// Market endpoints
ESI::market($regionId)->orders();
ESI::market($regionId)->history($typeId);
```

### Dependency Injection

```php
use WormholeSystems\ESI\ESI;

class CharacterController extends Controller
{
    public function __construct(private ESI $esi)
    {
    }

    public function show($characterId)
    {
        $character = $this->esi->character($characterId)->info();
        
        return view('character.show', compact('character'));
    }
}
```

### Batch Operations

Efficiently retrieve data for multiple items:

```php
// Get multiple character names at once
$names = ESI::universe()->names([
    123456789,
    987654321,
    456789123,
]);

// Get multiple types
$types = ESI::universe()->types([1, 2, 3, 4, 5]);
```

### Caching

Responses are automatically cached based on ESI cache headers:

```php
// This will be cached automatically
$alliances = ESI::alliances()->all(); // Cached for duration specified by ESI

// Disable caching for a specific call
$alliances = ESI::alliances()->withoutCache()->all();

// Manually cache with custom duration
$data = ESI::character($id)->info()->cache(3600); // Cache for 1 hour
```

### Error Handling

```php
use WormholeSystems\ESI\Exceptions\ESIException;
use WormholeSystems\ESI\Exceptions\RateLimitException;
use WormholeSystems\ESI\Exceptions\CharacterNotFoundException;

try {
    $character = ESI::character(123456789)->info();
} catch (CharacterNotFoundException $e) {
    // Character doesn't exist
} catch (RateLimitException $e) {
    // Hit rate limit, retry later
    $retryAfter = $e->getRetryAfter();
} catch (ESIException $e) {
    // General ESI error
    Log::error('ESI Error: ' . $e->getMessage());
}
```

### Events

Listen to ESI events in your `EventServiceProvider`:

```php
use WormholeSystems\ESI\Events\ESIRequestCompleted;
use WormholeSystems\ESI\Events\ESIRequestFailed;
use WormholeSystems\ESI\Events\TokenRefreshed;

protected $listen = [
    ESIRequestCompleted::class => [
        LogESIRequest::class,
    ],
    TokenRefreshed::class => [
        UpdateStoredToken::class,
    ],
];
```

## Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

Run static analysis:

```bash
composer analyse
```

### Testing Your Application

The package provides testing utilities:

```php
use WormholeSystems\ESI\Facades\ESI;

// In your tests
ESI::fake([
    'character.*.info' => [
        'name' => 'Test Character',
        'corporation_id' => 123456,
    ],
]);

// Your test code
$character = ESI::character(123456789)->info();
$this->assertEquals('Test Character', $character->name);

// Assert ESI was called
ESI::assertCalled('character.*.info');
```

## Roadmap

This package is under active development. Here's what's planned:

### Phase 1: Core Foundation ✨ (Current)
- [x] Project structure and setup
- [ ] HTTP client with rate limiting
- [ ] OAuth 2.0 authentication flow
- [ ] Token management and refresh
- [ ] Basic error handling and exceptions
- [ ] Configuration system

### Phase 2: Essential Endpoints
- [ ] Character endpoints (public info, skills, assets)
- [ ] Corporation endpoints (info, members)
- [ ] Universe endpoints (systems, stations, types)
- [ ] Market endpoints (orders, history, prices)
- [ ] Alliance endpoints

### Phase 3: Advanced Features
- [ ] Automatic response caching with ESI headers
- [ ] Event system for API calls
- [ ] Queue integration for background jobs
- [ ] Batch operations and parallel requests
- [ ] Response models with type safety

### Phase 4: Full Coverage
- [ ] All remaining ESI endpoints
- [ ] Industry endpoints
- [ ] Mail and notification endpoints
- [ ] Contacts and standings
- [ ] Killmail endpoints
- [ ] Sovereignty and FW endpoints

### Phase 5: Developer Experience
- [ ] Comprehensive documentation
- [ ] Code generation from ESI swagger spec
- [ ] Testing utilities and fakes
- [ ] Performance optimizations
- [ ] Laravel debugbar integration

## Available ESI Endpoints

This package will provide access to all ESI endpoint categories:

- **Alliance**: Alliance information and member corporations
- **Assets**: Character and corporation asset listings
- **Bookmarks**: Personal and corporation bookmarks
- **Calendar**: Character calendar and events
- **Character**: Character information, skills, standings
- **Clones**: Jump clones and implants
- **Contacts**: Character and corporation contacts
- **Contracts**: Character and corporation contracts
- **Corporation**: Corporation details, members, structures
- **Dogma**: Attributes and effects
- **Fittings**: Character fittings
- **Fleets**: Fleet management
- **Incursions**: Active incursions
- **Industry**: Manufacturing jobs and facilities
- **Insurance**: Insurance prices
- **Killmails**: Kill reports and statistics
- **Location**: Character location and online status
- **Loyalty**: Loyalty point stores and offers
- **Mail**: Character mail
- **Market**: Market orders, prices, history
- **Opportunities**: Opportunities system
- **Planetary Interaction**: PI colonies and schematics
- **Routes**: Route planning
- **Search**: Search across EVE data
- **Skills**: Character skills and queue
- **Sovereignty**: Sovereignty structures and campaigns
- **Status**: Server status
- **Universe**: Systems, stations, planets, types, etc.
- **Wallet**: Character and corporation wallets
- **Wars**: War information

## Requirements

- PHP 8.4 or higher
- Laravel 11.0 or 12.0
- Redis (recommended for caching)

## Resources

### EVE Online ESI Documentation
- [ESI Documentation](https://esi.evetech.net/ui/)
- [EVE Developers Portal](https://developers.eveonline.com/)
- [ESI Swagger Spec](https://esi.evetech.net/latest/swagger.json)
- [EVE Third Party Developers Discord](https://discord.gg/eveonline)

### EVE Online Resources
- [EVE Online](https://www.eveonline.com/)
- [EVE University](https://wiki.eveuniversity.org/)
- [EVE Developers](https://developers.eveonline.com/)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! We're looking for help with:

- Adding endpoint implementations
- Writing tests
- Improving documentation
- Reporting bugs
- Suggesting features

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover a security vulnerability within this package, please send an email to nicolaskion07@gmail.com. All security vulnerabilities will be promptly addressed.

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [NicolasKion](https://github.com/NicolasKion)
- [All Contributors](../../contributors)

Special thanks to:
- CCP Games for EVE Online and the ESI API
- The EVE Online third-party developer community

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Disclaimer

This package is not affiliated with, endorsed by, or sponsored by CCP Games. EVE Online and the EVE logo are the registered trademarks of CCP hf. All rights are reserved by CCP Games.
