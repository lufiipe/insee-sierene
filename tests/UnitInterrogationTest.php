<?php

namespace LuFiipe\InseeSierene\Tests;

use DateTime;
use LuFiipe\InseeSierene\Exception\SireneException;
use LuFiipe\InseeSierene\Parameters\UnitParameters;

/**
 * Unit query tester
 */
class UnitInterrogationTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testInformations(): void
    {
        $result = self::$sirene->informations();
        $this->assertEquals(200, $result->getHeader()->getStatus());
        $this->assertIsArray($result->getBody()['etatsDesServices']);
        $this->assertNotEmpty($result->getBody()['etatsDesServices']);
    }

    /**
     * @return void
     */
    public function testSiret(): void
    {
        //  Insee's Siret
        $siret = '12002701600563';

        $result = self::$sirene->siret($siret);
        $this->assertEquals(200, $result->getHeader()->getStatus());
        $this->assertNotEmpty($result->getBody()['siret']);
        $this->assertEquals($siret, $result->getBody()['siret']);
    }

    /**
     * @return void
     */
    public function testSiretWithUnitParameters(): void
    {
        // Expected query string version
        $expected = 'champs=denominationUsuelleEtablissement%2Censeigne1Etablissement&date=2018-03-01&masquerValeursNulles=true';

        // Siret of Insee
        $siret = '12002701600563';

        // Query to test
        $parameters = (new UnitParameters)
            ->setDate(new DateTime('2018-03-01'))
            ->setFields(['enseigne1Etablissement', 'denominationUsuelleEtablissement'])
            ->setHideNull(true);
        $result = self::$sirene->siret($siret, $parameters);
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testSiretWithBadFormat(): void
    {
        $this->expectException(SireneException::class);
        self::$sirene->siret('1234567890123');
    }

    /**
     * @return void
     */
    public function testSiren(): void
    {
        // Insee's Siren
        $siren = '120027016';

        $result = self::$sirene->siren($siren);
        $this->assertEquals(200, $result->getHeader()->getStatus());
        $this->assertNotEmpty($result->getBody()['siren']);
        $this->assertEquals($siren, $result->getBody()['siren']);
    }

    /**
     * @return void
     */
    public function testSirenWithUnitParameters(): void
    {
        // Expected query string version
        $expected = 'champs=denominationUniteLegale%2CeconomieSocialeSolidaireUniteLegale&date=2018-12-30&masquerValeursNulles=true';

        // Siren of Insee
        $siren = '120027016';

        // Query to test
        $date = '2018-12-30';
        $parameters = (new UnitParameters)
            ->setDate(new DateTime($date))
            ->setFields(['denominationUniteLegale', 'economieSocialeSolidaireUniteLegale'])
            ->setHideNull(true);
        $result = self::$sirene->siren($siren, $parameters);
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testSirenWithBadFormat(): void
    {
        $this->expectException(SireneException::class);
        self::$sirene->siren('12345678');
    }
}
