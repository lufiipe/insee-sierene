<?php

namespace LuFiipe\InseeSierene\Tests;

use LuFiipe\InseeSierene\Utils\Insee;

/**
 * INSEE utils tester
 */
class UtilsInseeTest extends AbstractTestCase
{
    /**
     * @dataProvider provideValidSirens
     * 
     * @return void
     */
    public function testIsValidSiren(string $siren): void
    {
        $isValid = Insee::isValidSiren($siren);
        $this->assertTrue($isValid);
    }

    /**
     * @dataProvider provideWrongSirens
     * 
     * @return void
     */
    public function testNotValidSiren(string $siren): void
    {
        $isValid = Insee::isValidSiren($siren);
        $this->assertFalse($isValid);
    }

    /**
     * @dataProvider provideValidSirets
     * 
     * @return void
     */
    public function testIsValidSiret(string $siret): void
    {
        $isValid = Insee::isValidSiret($siret);
        $this->assertTrue($isValid);
    }

    /**
     * @dataProvider provideWrongSirens
     * 
     * @return void
     */
    public function testNotValidSiret(string $siren): void
    {
        $isValid = Insee::isValidSiren($siren);
        $this->assertFalse($isValid);
    }
}
