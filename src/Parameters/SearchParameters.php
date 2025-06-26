<?php

namespace LuFiipe\InseeSierene\Parameters;

use LuFiipe\InseeSierene\Parameters\Contracts\FacetInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\FieldInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\PeriodInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\QueryInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\SortInterface;
use LuFiipe\InseeSierene\Parameters\Traits\FacetableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\FieldableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\PaginableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\PeriodableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\QueryableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\SortableTrait;

/**
 * Advanced search parameters
 */
class SearchParameters implements PaginatedSearchInterface, QueryInterface, PeriodInterface, FieldInterface, FacetInterface, SortInterface
{
    use QueryableTrait;
    use PeriodableTrait;
    use FieldableTrait;
    use FacetableTrait;
    use SortableTrait;
    use PaginableTrait;

    /**
     * Returns the parameters to use in a HTTP message body as an array.
     *
     * @return array<mixed>
     */
    public function toRequestBody(): array
    {
        $requestBody = [];

        // If use Facet, force per page to 0
        if ($this->getFacet()) {
            $this->setPerPage(0);
        }

        // Merge QueryableTrait
        $requestBody += $this->queryableToArray();

        // Merge PeriodableTrait
        $requestBody += $this->periodableToArray();

        // Merge FieldableTrait
        $requestBody += $this->fieldableToArray();

        // Merge FacetableTrait
        $requestBody += $this->facetableToArray();

        // Merge SortableTrait
        $requestBody += $this->sortableToArray();

        // Merge PaginableTrait
        $requestBody += $this->paginableToArray();

        return $requestBody;
    }
}
