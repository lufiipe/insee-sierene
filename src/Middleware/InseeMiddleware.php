<?php

namespace LuFiipe\InseeSierene\Middleware;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\RetryMiddleware;
use LuFiipe\InseeSierene\Events;
use LuFiipe\SimplEvent\Event;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Functions used to create Insee's middleware
 */
class InseeMiddleware
{
    /**
     * Mapping table of retries and delays
     *
     * @var array<int>
     */
    public static $delays = [];

    /**
     * Middleware that implements automatic retry of requests when HTTP servers respond with a 429 status codes
     *
     * @param integer $maxRetries Maximum number of attempts 
     * @return callable
     */
    public static function retryOnRateLimit(int $maxRetries = 10): callable
    {
        // Returns true if the request is to be retried
        $decider = (function (
            int $retries,
            RequestInterface $request,
            ?PsrResponseInterface $response = null,
            ?GuzzleException $exception = null
        ) use ($maxRetries): bool {
            // Limit the number of retries
            if ($retries >= $maxRetries) {
                return false;
            }

            // Retry on 429 error
            if (!$response || $response->getStatusCode() !== 429) {
                return false;
            }

            // Stores the delay to be used in the $delay closure
            self::$delays[$retries] = self::determineDelay($response);

            return true;
        });

        // Number of milliseconds to delay
        $delay = (function (int $retries, PsrResponseInterface $response): int {

            // Default exponential backoff delay
            $exponentialDelay = RetryMiddleware::exponentialDelay($retries);

            $delayMs = self::$delays[$retries - 1] ?? $exponentialDelay;

            // Emit a 'rate limit reached' event
            Event::emit(Events::RATE_LIMIT_REACHED, $delayMs, $retries);

            return $delayMs;
        });

        return Middleware::retry($decider, $delay);
    }

    /**
     * Determine the delay timeout in milliseconds
     * 
     * Attempts to read and interpret the configured 
     * X-Rate-Limit-Reset or Retry-After header
     *
     * @param PsrResponseInterface|null $response
     * @return integer
     */
    private static function determineDelay(?PsrResponseInterface $response = null): int
    {
        $nowMs = (int) microtime(true) * 1000;
        $delayMs = 0;

        if ($response) {
            // First, check the "X-Rate-Limit-Reset" header. (timestamp in ms)
            if ($response->hasHeader('X-Rate-Limit-Reset')) {
                $resetAt = $response->getHeaderLine('X-Rate-Limit-Reset');

                // Convert to milliseconds if needed
                if (preg_match('/^\d{10}$/', $resetAt)) {
                    $resetAt = (int) $resetAt * 1000;
                }

                if (preg_match('/^\d{13}$/', (string) $resetAt)) {
                    $delayMs = max(0, (int) $resetAt - $nowMs);
                }
            }

            // Fallback to Retry-After if needed (timestamp in seconds)
            if ($delayMs === 0 && $response->hasHeader('Retry-After')) {
                $retryAfter = $response->getHeaderLine('Retry-After');

                if (is_numeric($retryAfter)) {
                    $delayMs = (int) $retryAfter * 1000;
                }
            }
        }

        return $delayMs;
    }
}
