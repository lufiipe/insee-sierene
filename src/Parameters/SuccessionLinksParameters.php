<?php

namespace LuFiipe\InseeSierene\Parameters;

use LuFiipe\InseeSierene\Parameters\Contracts\QueryInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\SortInterface;
use LuFiipe\InseeSierene\Parameters\Traits\PaginableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\QueryableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\SortableTrait;

/**
 * Succession links parameters
 */
class SuccessionLinksParameters implements PaginatedSearchInterface, QueryInterface, SortInterface
{
    use QueryableTrait;
    use SortableTrait;
    use PaginableTrait;

    /**
     * Returns the succession links parameters to use in a HTTP message body as an array.
     *
     * @return array<mixed>
     */
    public function toRequestBody(): array
    {
        $requestBody = [];

        // Merge QueryableTrait
        $requestBody += $this->queryableToArray();

        // Merge SortableTrait
        $requestBody += $this->sortableToArray();

        // Merge PaginableTrait
        $requestBody += $this->paginableToArray();

        return $requestBody;
    }
}
