<?php

namespace LuFiipe\InseeSierene\Tests;

use GuzzleHttp\Psr7\Query;
use LuFiipe\InseeSierene\InseeAbstract;
use LuFiipe\InseeSierene\Parameters\ParametersInterface;
use LuFiipe\InseeSierene\Sirene;
use LuFiipe\InseeSierene\Utils\Arrays;
use PHPUnit\Framework\TestCase;

/**
 * Base tester
 */
class AbstractTestCase extends TestCase
{
    /**
     * Sirene Client
     *
     * @var Sirene
     */
    protected static Sirene $sirene;

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {
        // Create new Sirene client before the first test of this test class is run
        self::$sirene = self::buildClient();
    }

    /**
     * 
     *
     * @return Sirene
     */
    /**
     * Return a new Sirene Client
     *
     * @param boolean $useRetryOnRateLimit Indicates if the retry on rate limit middleware is used
     * @param callable|null $handler HTTP handler function to use with the Guzzle stack
     * @return Sirene
     */
    public static function buildClient(bool $useRetryOnRateLimit = true, ?callable $handler = null): Sirene
    {
        $apiKey = (string) getenv('INSEE_API_KEY');
        $timeOut = (float) (getenv('INSEE_API_TIMEOUT') ?: InseeAbstract::TIME_OUT_DEFAULT);

        return new Sirene($apiKey, $useRetryOnRateLimit, $timeOut, $handler);
    }

    /**
     * Asserts that two URL parameters are equal
     *
     * @param string $expected Query string
     * @param ParametersInterface $actual Query parameters
     * @return void
     */
    final protected function assertSameQueryStrings(string $expected, ParametersInterface $actual): void
    {
        $expected = Query::parse($expected);
        $expected = Arrays::removeEmptyElements($expected);
        $expected = Arrays::sortElementsWithSeparator($expected, ',');
        ksort($expected);
        $expected = Query::build($expected);

        $actual = $actual->toRequestBody();
        $actual = Arrays::removeEmptyElements($actual);
        $actual = Arrays::sortElementsWithSeparator($actual, ',');
        ksort($actual);
        $actual = Query::build($actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Valid SIREN data provider
     *
     * @return array<int, list<string>>
     */
    public static function provideValidSirens(): array
    {
        return [
            ['120027016'], // Siren "INSEE"
            ['356000000'], // Siren "La Poste" (N° siren spécial)
        ];
    }

    /**
     * Invalid SIREN data provider
     *
     * @return array<int, list<string>>
     */
    public static function provideWrongSirens(): array
    {
        return [
            ['123456789'],
            ['120027017'],
        ];
    }

    /**
     * Valid SIRET data provider
     *
     * @return array<int, list<string>>
     */
    public static function provideValidSirets(): array
    {
        return [
            ['12002701600563'], // Siret "INSEE"
        ];
    }

    /**
     * Invalid SIRET data provider
     *
     * @return array<int, list<string>>
     */
    public static function provideWrongSirets(): array
    {
        return [
            ['12345678901234'],
            ['12002701600564'],
        ];
    }
}
