<?php

namespace LuFiipe\InseeSierene\Tests;

use LuFiipe\InseeSierene\Parameters\Contracts\SortInterface;
use LuFiipe\InseeSierene\Parameters\SuccessionLinksParameters;
use LuFiipe\InseeSierene\Response\Header;
use LuFiipe\InseeSierene\Response\Pagination;

/**
 * Succession links tester
 */
class SuccessionLinksParametersTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testSearchEstablishmentsSuccessions(): void
    {
        // Expected query string version
        $expected = 'q=siretEtablissementPredecesseur:30613890001294&tri=successeur&nombre=1';

        // Query to test
        $parameters = (new SuccessionLinksParameters)
            ->setQuery('siretEtablissementPredecesseur:30613890001294')
            ->addSort(SortInterface::SORT_SIRET_ETABLISSEMENT_SUCCESSEUR)
            ->setPerPage(1);
        $collection = self::$sirene->searchEstablishmentsSuccessions($parameters);

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
    public function testSearchEstablishmentsSuccessionsCurseur(): void
    {
        // Expected query string version
        $expected = 'q=dateLienSuccession:[2020-12-20 TO 2020-12-22]&nombre=1000&curseur=*';

        // Query to test
        $parameters = (new SuccessionLinksParameters)
            ->setQuery('dateLienSuccession:[2020-12-20 TO 2020-12-22]')
            ->setPerPage(1000)
            ->withCursor();
        $collection = self::$sirene->searchEstablishmentsSuccessions($parameters);

        $this->assertSameQueryStrings($expected, $parameters);

        $total = $collection->count();
        $count = 0;
        $collection->each(function (array $item, int $key, Pagination $pagination, Header $header) use (&$count) {
            $this->assertEquals(200, $header->getStatus());
            $count++;
        });
        $this->assertEquals($count, $total);
    }
}
