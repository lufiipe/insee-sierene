<?php

namespace LuFiipe\InseeSierene\Tests;

use DateTime;
use LuFiipe\InseeSierene\Parameters\SearchParameters;
use LuFiipe\InseeSierene\Response\Header;
use LuFiipe\InseeSierene\Response\Pagination;

/**
 * Advanced search tester
 */
class SearchParametersTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testBusinessVariableNotHistorized(): void
    {
        $parameters = (new SearchParameters)
            ->setQuery('siren:120027016');
        $firstPage = self::$sirene->searchLegalUnits($parameters)->firstPage();
        $this->assertEquals(200, $firstPage->getHeader()->getStatus());
    }

    /**
     * @return void
     */
    public function testEstablishmentVariableNotHistorized(): void
    {
        $parameters = (new SearchParameters)
            ->setQuery('siret:12002701600563');
        $firstPage = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $firstPage->getHeader()->getStatus());
    }

    /**
     * @return void
     */
    public function testBusinessVariableHistorized(): void
    {
        $parameters = (new SearchParameters)
            ->setQuery('periode(denominationUniteLegale:GAZ)');
        $firstPage = self::$sirene->searchLegalUnits($parameters)->firstPage();
        $this->assertEquals(200, $firstPage->getHeader()->getStatus());
    }

    /**
     * @return void
     */
    public function testEstablishmentVariableHistorized(): void
    {
        $parameters = (new SearchParameters)
            ->setQuery('periode(activitePrincipaleEtablissement:33.01)');
        $firstPage = self::$sirene->searchEstablishments($parameters)->firstPage();
        $this->assertEquals(200, $firstPage->getHeader()->getStatus());
    }

    /**
     * @return void
     */
    public function testsearchLegalUnitsWithPagination(): void
    {
        // Expected query string version
        $expected = 'q=periode(denominationUniteLegale:"METEO-FRANCE")&date=2008-01-01&champs=siren,denominationUniteLegale&masquerValeursNulles=true&tri=dateCreationUniteLegale%20asc&nombre=4';

        // Query to test
        $parameters = (new SearchParameters)
            ->setQuery('periode(denominationUniteLegale:"METEO-FRANCE")')
            ->setDate(new DateTime('2008-01-01'))
            ->setFields(['siren', 'denominationUniteLegale'])
            ->setHideNull(true)
            ->addSort('dateCreationUniteLegale')
            ->setPerPage(4);
        $collection = self::$sirene->searchLegalUnits($parameters);

        $this->assertSameQueryStrings($expected, $parameters);

        $total = $collection->count();
        $count = 0;
        $collection->each(function (array $item, int $key, Pagination $pagination, Header $header) use (&$count) {
            $this->assertEquals(200, $header->getStatus());
            $count++;
        });
        $this->assertEquals($count, $total);
    }

    /**
     * @return void
     */
    public function testSearchEstablishmentsWithPagination(): void
    {
        // Expected query string version
        $queryString = 'q=denominationUniteLegale:"METEO-FRANCE"%20AND%20dateCreationEtablissement:"1993-11-01"&champs=siret,denominationUniteLegale,dateCreationUniteLegale&masquerValeursNulles=true&tri=siret%20asc&nombre=5';

        // Query to test
        $parameters = (new SearchParameters)
            ->setQuery('denominationUniteLegale:"METEO-FRANCE" AND dateCreationEtablissement:"1993-11-01"')
            ->setFields(['siret', 'denominationUniteLegale', 'dateCreationUniteLegale'])
            ->setHideNull(true)
            ->addSort('siret')
            ->setPerPage(5);
        $collection = self::$sirene->searchEstablishments($parameters);

        $this->assertSameQueryStrings($queryString, $parameters);

        $total = $collection->count();
        $count = 0;
        $collection->each(function (array $item, int $key, Pagination $pagination, Header $header) use (&$count) {
            $this->assertEquals(200, $header->getStatus());
            $count++;
        });
        $this->assertEquals($count, $total);
    }

    /**
     * @return void
     */
    public function testSearchEstablishmentsWithCursor(): void
    {
        // Expected query string version
        $expected = 'q=periode(etatAdministratifEtablissement:A)%20AND%20categorieJuridiqueUniteLegale:92*%20AND%20codeCommuneEtablissement:75101&champs=siret,denominationUniteLegale,dateCreationUniteLegale&nombre=1000&curseur=*';

        // Query to test
        $parameters = (new SearchParameters)
            ->setQuery('periode(etatAdministratifEtablissement:A) AND categorieJuridiqueUniteLegale:92* AND codeCommuneEtablissement:75101')
            ->setFields(['siret', 'denominationUniteLegale', 'dateCreationUniteLegale'])
            ->setPerPage(1000)
            ->withCursor();
        $collection = self::$sirene->searchEstablishments($parameters);

        $this->assertSameQueryStrings($expected, $parameters);

        $total = $collection->count();
        $count = 0;
        $collection->each(function (array $item, int $key, Pagination $pagination, Header $header) use (&$count) {
            $this->assertEquals(200, $header->getStatus());
            $count++;
        });
        $this->assertEquals($count, $total);
    }

    /**
     * @return void
     */
    public function testsearchLegalUnitsManualPagination(): void
    {
        // Expected query string version
        $expected = 'q=periode(denominationUniteLegale:"METEO-FRANCE")&date=2008-01-01&champs=siren,denominationUniteLegale&masquerValeursNulles=true&tri=dateCreationUniteLegale%20asc&nombre=4';

        // Query to test
        $parameters = (new SearchParameters)
            ->setQuery('periode(denominationUniteLegale:"METEO-FRANCE")')
            ->setDate(new DateTime('2008-01-01'))
            ->setFields(['siren', 'denominationUniteLegale'])
            ->setHideNull(true)
            ->addSort('dateCreationUniteLegale')
            ->setPerPage(4);
        $collection = self::$sirene->searchLegalUnits($parameters);

        $this->assertSameQueryStrings($expected, $parameters);

        // This test only works if the result returns 8 elements
        $total = $collection->count();
        $this->assertEquals(8, $total);
        if (8 === $total) {
            $firstPage = $collection->firstPage();
            $this->assertEquals(200, $firstPage->getHeader()->getStatus());
            $this->assertCount(4, $firstPage->getBody());

            $nextPage = $collection->nextPage();
            $this->assertEquals(200, $nextPage->getHeader()->getStatus());
            $this->assertCount(4, $nextPage->getBody());

            $nextPage = $collection->nextPage();
            $this->assertEquals(200, $nextPage->getHeader()->getStatus());
            $this->assertCount(0, $nextPage->getBody());

            $previousPage = $collection->previousPage();
            $this->assertEquals(200, $previousPage->getHeader()->getStatus());
            $this->assertCount(4, $previousPage->getBody());

            $previousPage = $collection->previousPage();
            $this->assertEquals(200, $previousPage->getHeader()->getStatus());
            $this->assertCount(4, $previousPage->getBody());

            $lastPage = $collection->lastPage();
            $this->assertEquals(200, $lastPage->getHeader()->getStatus());
            $this->assertCount(4, $lastPage->getBody());
        }
    }

    /**
     * @return void
     */
    public function testsearchLegalUnitsManualPaginationWithOffset(): void
    {
        // Expected query string version
        $expected = 'q=periode(denominationUniteLegale:"METEO-FRANCE")&date=2008-01-01&champs=siren,denominationUniteLegale&masquerValeursNulles=true&tri=dateCreationUniteLegale%20asc&nombre=4&debut=2';

        // Query to test
        $parameters = (new SearchParameters)
            ->setQuery('periode(denominationUniteLegale:"METEO-FRANCE")')
            ->setDate(new DateTime('2008-01-01'))
            ->setFields(['siren', 'denominationUniteLegale'])
            ->setHideNull(true)
            ->addSort('dateCreationUniteLegale')
            ->setPerPage(4)
            ->setOffset(2);
        $collection = self::$sirene->searchLegalUnits($parameters);

        $this->assertSameQueryStrings($expected, $parameters);

        // This test only works if the result returns 8 elements
        $total = $collection->count();
        $this->assertEquals(8, $total);
        if (8 === $total) {
            $firstPage = $collection->firstPage();
            $this->assertEquals(200, $firstPage->getHeader()->getStatus());
            $this->assertCount(4, $firstPage->getBody());

            $nextPage = $collection->nextPage();
            $this->assertEquals(200, $nextPage->getHeader()->getStatus());
            $this->assertCount(2, $nextPage->getBody());

            $nextPage = $collection->nextPage();
            $this->assertEquals(200, $nextPage->getHeader()->getStatus());
            $this->assertCount(0, $nextPage->getBody());

            $previousPage = $collection->previousPage();
            $this->assertEquals(200, $previousPage->getHeader()->getStatus());
            $this->assertCount(2, $previousPage->getBody());

            $previousPage = $collection->previousPage();
            $this->assertEquals(200, $previousPage->getHeader()->getStatus());
            $this->assertCount(4, $previousPage->getBody());

            $lastPage = $collection->lastPage();
            $this->assertEquals(200, $lastPage->getHeader()->getStatus());
            $this->assertCount(4, $lastPage->getBody());
        }
    }
}
