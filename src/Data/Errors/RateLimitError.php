<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Data\Errors;

use WormholeSystems\ESI\Data\ESIError;

/**
 * Represents a rate limit error (HTTP 429).
 */
final class RateLimitError extends ESIError {}
