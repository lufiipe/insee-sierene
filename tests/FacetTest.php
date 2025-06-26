<?php

namespace LuFiipe\InseeSierene\Tests;

use LuFiipe\InseeSierene\Parameters\Facet;
use LuFiipe\InseeSierene\Parameters\SearchParameters;

/**
 * Facet tester
 */
class FacetTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testParameterMin(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale%3A57*%20AND%20nombrePeriodesEtablissement%3A%5B6%20TO%2015%5D&facette.champ=categorieJuridiqueUniteLegale%2CnombrePeriodesEtablissement&nombre=0&facette.min=100';

        // Query to test (min parameter)
        $min = 100;
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD, 'categorieJuridiqueUniteLegale')
            ->addSetting(Facet::FACET_SETTING_FIELD, 'nombrePeriodesEtablissement')
            ->addSetting(Facet::FACET_SETTING_FIELD_MIN, $min);
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:57* AND nombrePeriodesEtablissement:[6 TO 15]')
            ->setFacet($facet)
            ->setOffset(0);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testParameterMinField(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale%3A57*%20AND%20nombrePeriodesEtablissement%3A%5B6%20TO%2015%5D&facette.champ=categorieJuridiqueUniteLegale%2CnombrePeriodesEtablissement&nombre=0&facette.categorieJuridiqueUniteLegale.min=100';

        // Query to test (min parameter on field)
        $min = 100;
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD, 'nombrePeriodesEtablissement')
            ->addSetting(Facet::FACET_SETTING_FIELD_MIN, $min, 'categorieJuridiqueUniteLegale');
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:57* AND nombrePeriodesEtablissement:[6 TO 15]')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testParameterSort(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale:1000%20AND%20codeCommuneEtablissement:75*&facette.champ=etatAdministratifEtablissement,codeCommuneEtablissement&nombre=0&facette.codeCommuneEtablissement.nombre=20&facette.tri=index:asc';

        // Query to test (parameter sort ascending on values)
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD, 'etatAdministratifEtablissement')
            ->addSetting(Facet::FACET_SETTING_FIELD_NOMBRE, 20, 'codeCommuneEtablissement')
            ->addSetting(Facet::FACET_SETTING_FIELD_TRI, Facet::FACET_SETTING_FIELD_TRI_INDEX_ASC);
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:1000 AND codeCommuneEtablissement:75*')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testParameterSortField(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale:1000%20AND%20codeCommuneEtablissement:75*&facette.champ=etatAdministratifEtablissement,codeCommuneEtablissement&nombre=0&facette.codeCommuneEtablissement.nombre=20&facette.codeCommuneEtablissement.tri=index:asc&facette.etatAdministratifEtablissement.tri=count:desc';

        // Query to test (ascending sort parameter on value for the district and descending on occurrences for the administrative state)
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD_NOMBRE, 20, 'codeCommuneEtablissement')
            ->addSetting(Facet::FACET_SETTING_FIELD_TRI, Facet::FACET_SETTING_FIELD_TRI_INDEX_ASC, 'codeCommuneEtablissement')
            ->addSetting(Facet::FACET_SETTING_FIELD_TRI, Facet::FACET_SETTING_FIELD_TRI_COUNT_DESC, 'etatAdministratifEtablissement');
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:1000 AND codeCommuneEtablissement:75*')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testParameterManquant(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale:1000&facette.champ=sexeUniteLegale&nombre=0&facette.manquant=true';

        // Query to test (missing parameter)
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD, 'sexeUniteLegale')
            ->addSetting(Facet::FACET_SETTING_FIELD_MANQUANT, true);
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:1000')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testParameterTotal(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale:1000&facette.champ=sexeUniteLegale&nombre=0&facette.total=true';

        // Query to test (total parameter)
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD, 'sexeUniteLegale')
            ->addSetting(Facet::FACET_SETTING_FIELD_TOTAL, true);
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:1000')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testParameterModalite(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale:1000%20AND%20codeCommuneEtablissement:78*&facette.champ=codeCommuneEtablissement&nombre=0&facette.modalite=true';

        // Query to test (modality parameter)
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD, 'codeCommuneEtablissement')
            ->addSetting(Facet::FACET_SETTING_FIELD_MODALITE, true);
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:1000 AND codeCommuneEtablissement:78*')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testParameterPrefixe(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale:1000%20AND%20prenom1UniteLegale:*&facette.champ=prenom1UniteLegale&nombre=0&facette.prefixe=P';

        // Query to test (prefix parameter)
        $facet = (new Facet)
            ->addSetting(Facet::FACET_SETTING_FIELD, 'prenom1UniteLegale')
            ->addSetting(Facet::FACET_SETTING_FIELD_PREFIXE, 'P');
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:1000 AND prenom1UniteLegale:*')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testRequetesMulticriteres(): void
    {
        // Expected query string version
        $expected = 'q=categorieJuridiqueUniteLegale:5499%20AND%20codeCommuneEtablissement:78*&nombre=0&facette.requete=monSecteur&facette.champ=codeCommuneEtablissement&facette.monSecteur.q=codeCommuneEtablissement:78646%20OR%20codeCommuneEtablissement:78158%20OR%20codeCommuneEtablissement:78686';

        // Query to test (Advanced search queries)
        $facet = (new Facet)
            ->addQuery('monSecteur', 'codeCommuneEtablissement:78646 OR codeCommuneEtablissement:78158 OR codeCommuneEtablissement:78686');
        $parameters = (new SearchParameters)
            ->setQuery('categorieJuridiqueUniteLegale:5499 AND codeCommuneEtablissement:78*')
            ->setFacet($facet);
        $result = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testIntervalFieldNumeric(): void
    {
        // Expected query string version
        $expected = 'facette.intervalle=nombrePeriodesUniteLegale&facette.nombrePeriodesUniteLegale.demarrage=30&facette.nombrePeriodesUniteLegale.fin=71&facette.nombrePeriodesUniteLegale.pas=5&nombre=0';

        // Query to test (Interval test)
        $facet = (new Facet)
            ->addInterval('nombrePeriodesUniteLegale', 30, 71, 5);
        $parameters = (new SearchParameters)
            ->setFacet($facet);

        $result = self::$sirene->searchLegalUnits($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testIntervalFieldDate(): void
    {
        // Expected query string version
        $expected = 'nombre=0&facette.intervalle=dateCreationUniteLegale&facette.dateCreationUniteLegale.demarrage=NOW-3MONTH&facette.dateCreationUniteLegale.fin=NOW&facette.dateCreationUniteLegale.pas=%2B1MONTH';

        // Query to test (Interval test)
        $facet = (new Facet)
            ->addInterval('dateCreationUniteLegale', 'NOW-3MONTH', 'NOW', '+1MONTH');
        $parameters = (new SearchParameters)
            ->setFacet($facet);

        $result = self::$sirene->searchLegalUnits($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }

    /**
     * @return void
     */
    public function testIntervalOther(): void
    {
        // Expected query string version
        $expected = 'nombre=0&facette.intervalle=dateCreationUniteLegale&facette.dateCreationUniteLegale.demarrage=NOW/YEAR&facette.dateCreationUniteLegale.fin=NOW&facette.dateCreationUniteLegale.pas=%2B7DAY&facette.nombre=100&facette.autre=tout';

        // Query to test (Interval test with other parameter)
        $facet = (new Facet)
            ->addInterval('dateCreationUniteLegale', 'NOW/YEAR', 'NOW', '+7DAY')
            ->addIntervalSetting(Facet::FACET_SETTING_INTERVAL_AUTRE, Facet::FACET_SETTING_INTERVAL_AUTRE_TOUT)
            ->addSetting(Facet::FACET_SETTING_FIELD_NOMBRE, 100);
        $parameters = (new SearchParameters)
            ->setFacet($facet);

        $result = self::$sirene->searchLegalUnits($parameters)->firstPage();
        $this->assertEquals(200, $result->getHeader()->getStatus());

        $this->assertSameQueryStrings($expected, $parameters);
    }
}
