# Usage Examples

## Basic Usage

### Using the ESI Class

```php
use WormholeSystems\ESI\ESI;

// Get the ESI instance from the container
$esi = app(ESI::class);

// Or use dependency injection
class MyController {
    public function __construct(private ESI $esi) {}
}
```

### Getting Character Information

```php
$result = $esi->characters->get(123456789);

if ($result->isSuccess()) {
    $character = $result->data; // CharacterInfoDTO
    echo "Character: {$character->name}";
    echo "Corporation ID: {$character->corporationId}";
} else {
    $error = $result->error; // ESIError
    echo "Error: {$error->body['error']}";
    echo "Status: {$error->statusCode}";
}
```

### Using Callbacks

```php
$esi->characters->get(123456789)
    ->onSuccess(function ($character) {
        // Handle success
        echo "Found: {$character->name}";
    })
    ->onFailure(function ($error) {
        // Handle failure
        echo "Error: {$error->body['error']}";
    });
```

### Pattern Matching on Error Types

```php
use WormholeSystems\ESI\Data\Errors\{
    RateLimitError,
    AuthenticationError,
    NotFoundError
};

$result = $esi->characters->get(123456789);

if ($result->isFailure()) {
    $action = match (true) {
        $result->error instanceof RateLimitError => 
            'Retry after ' . $result->error->body['timeout'] . ' seconds',
        $result->error instanceof AuthenticationError => 
            'Refresh your token',
        $result->error instanceof NotFoundError => 
            'Character does not exist',
        default => 
            'Unknown error: ' . $result->error->body['error'],
    };
    
    echo $action;
}
```

### Mapping Results

```php
$characterName = $esi->characters->get(123456789)
    ->map(fn($char) => $char->name)
    ->unwrapOr('Unknown');
```

### Advanced: Direct Connector Usage

```php
use WormholeSystems\ESI\Requests\Character\GetCharacterInfo;

// Get the connector for advanced usage
$connector = $esi->getConnector();

// Send requests directly
$request = new GetCharacterInfo(123456789);
$result = $connector->sendAsResult($request);

// Or get the raw response
$response = $connector->send($request);
if ($response->successful()) {
    $data = $response->json();
}
```

### Using the Facade

```php
use WormholeSystems\ESI\Facades\ESI;

$result = ESI::characters->get(123456789);
```

## Error Handling

All ESI errors have two properties:

- `body` (array): The raw JSON response body
- `statusCode` (int): The HTTP status code

### Available Error Types

- `AuthenticationError` (401): Invalid or missing token
- `AuthorizationError` (403): Insufficient permissions/scopes
- `NotFoundError` (404): Resource doesn't exist
- `BadRequestError` (400): Invalid request parameters
- `ErrorLimitExceededError` (420): Too many errors
- `RateLimitError` (429): Too many requests
- `ServerError` (5xx): ESI server error

### Accessing Error Data

```php
$error = $result->error;

// Access any field from the response
$errorMessage = $error->body['error'];
$errorCode = $error->body['error_code'] ?? null;
$timeout = $error->body['timeout'] ?? null;
$scopes = $error->body['error_details']['required_scopes'] ?? null;

// Check status
$statusCode = $error->statusCode;
```

## Creating New Features

To add a new ESI endpoint category:

1. Create a new Feature class:

```php
namespace WormholeSystems\ESI\Features;

use WormholeSystems\ESI\Data\EsiResult;
use WormholeSystems\ESI\Http\Connector;

class CorporationFeature
{
    public function __construct(
        private Connector $connector
    ) {
    }

    public function get(int $corporationId): EsiResult
    {
        return $this->connector->sendAsResult(
            new GetCorporationInfo($corporationId)
        );
    }
}
```

2. Add it to the ESI class:

```php
readonly class ESI
{
    public CharacterFeature $characters;
    public AssetFeature $assets;
    public CorporationFeature $corporations; // Add this

    public function __construct(private Connector $connector)
    {
        $this->characters = new CharacterFeature($this->connector);
        $this->assets = new AssetFeature($this->connector);
        $this->corporations = new CorporationFeature($this->connector); // Add this
    }
}
```

3. Use it:

```php
$result = $esi->corporations->get(98765432);
```

