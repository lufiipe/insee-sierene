<?php

namespace LuFiipe\InseeSierene\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use LuFiipe\InseeSierene\Events;
use LuFiipe\SimplEvent\Event;

/**
 * Rate limiting tester
 */
class RateLimitMiddlewareTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testRateLimit(): void
    {
        // Responses: two 429s, then a 200
        $mock = new MockHandler([
            new Response(429, ['X-Rate-Limit-Reset' => (string)((int)(microtime(true) * 1000) + 100)]),
            new Response(429, ['Retry-After' => '1']),
            new Response(200, [], 'OK'),
        ]);

        $sirene = self::buildClient(true, $mock);

        $milliseconds = 0;
        $retries = 0;
        Event::on(Events::RATE_LIMIT_REACHED, function (int $ms, int $attempts)  use (&$milliseconds, &$retries) {
            $milliseconds = $ms;
            $retries = $attempts;
        });

        $result = $sirene->informations();

        $this->assertEquals(2, $retries); // Two retries
        $this->assertGreaterThanOrEqual(1000, $milliseconds); // Retry-After applied
        $this->assertGreaterThan(0, $milliseconds); // X-Rate-Limit-Reset applied
        $this->assertEquals(200, $result->getHeader()->getStatus());
    }
}
