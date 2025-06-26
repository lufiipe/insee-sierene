<?php

namespace LuFiipe\InseeSierene\Response;

use InvalidArgumentException;
use LuFiipe\InseeSierene\InseeAbstract;
use LuFiipe\InseeSierene\Parameters\Contracts\FacetInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\FieldInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\PaginationInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\SortInterface;
use LuFiipe\InseeSierene\Parameters\Facet;
use LuFiipe\InseeSierene\Parameters\PaginatedSearchInterface;
use LuFiipe\InseeSierene\Request\Request;
use LuFiipe\InseeSierene\Utils\VarType;

/**
 * INSEE Sirene response collection
 */
class Collection
{
    /**
     * Client Sirene
     *
     * @var InseeAbstract
     */
    private InseeAbstract $client;

    /**
     * Sirene request
     *
     * @var Request
     */
    private Request $request;

    /**
     * Construct the response collection
     * 
     * @param InseeAbstract $client Client Sirene
     * @param Request $request Sirene request
     */
    public function __construct(InseeAbstract $client, Request $request)
    {
        $this->client = $client;
        $this->request = $request;
    }

    /**
     * Returns true if pagination uses a facet
     *
     * @return boolean
     */
    protected function useFacet(): bool
    {
        $parameters = $this->request->getParameters();

        if ($parameters instanceof FacetInterface) {
            $facet = $parameters->getFacet();
            if ($facet instanceof Facet) {
                if (count($facet->getFacetFields()) > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Throw InvalidArgumentException if manual pagination using cursors cannot be performed
     * 
     * @param PaginationInterface $params
     * @return void
     */
    protected function failIfCursorPagination(PaginationInterface $params): void
    {
        if ($params->isCursorEnabled()) {
            throw new InvalidArgumentException('Manual page pagination doesn\'t work with cursor. Use the each() method instead.');
        }
    }

    /**
     * Returns the HTTP body parameters supported for collection-based requests
     *
     * @return PaginatedSearchInterface
     */
    protected function getRequestParametersPaginable(): PaginatedSearchInterface
    {
        $params = $this->request->getParameters();

        if (!$params instanceof PaginationInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Request parameter "%s" must implement "%s".',
                    VarType::getTypeName($params),
                    PaginationInterface::class
                )
            );
        }

        /** @var PaginatedSearchInterface */
        return $params;
    }

    /**
     * Returns the total number of items in the collection
     *
     * @return integer
     */
    public function count(): int
    {
        // Replicates the original request parameters for use in a separated count query
        $countRequest = clone $this->request;
        $countClient = clone $this->client;
        $params = $countRequest->getParameters();
        $countParams = $params !== null ? clone $params : null;

        if (!$countParams instanceof PaginationInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Request parameter "%s" must implement "%s".',
                    VarType::getTypeName($countParams),
                    PaginationInterface::class
                )
            );
        }

        // Parameters for a count operation
        $countParams->setPerPage(0)
            ->clearCursor();
        if ($countParams instanceof SortInterface) {
            $countParams->setSorts([]);
        }
        if ($countParams instanceof FieldInterface) {
            $countParams->setFields([]);
        }
        $countRequest->setParameters($countParams);

        // Execute the count query using duplicated parameters to avoid interfering with the ongoing query for this collection
        $response = $countClient->request($countRequest);
        $responsePaginated = new ResponsePaginated($response);

        unset($countRequest, $countParams, $countClient);

        return $responsePaginated->getPagination()->getTotal();
    }

    /**
     * Returns the first page of results
     *
     * @return ResponsePaginated
     */
    public function firstPage(): ResponsePaginated
    {
        $params = $this->getRequestParametersPaginable();

        $this->failIfCursorPagination($params);

        // If an offset is already defined, the loop will start from this offset; if not, it defaults to 0       
        $offset = ($params->getOffset() > 0) ? $params->getOffset() : 0;
        $params->setOffset($offset);

        $this->request->setParameters($params);

        $response = $this->client->request($this->request);

        return new ResponsePaginated($response);
    }

    /**
     * Returns the result of the last pagination
     *
     * @return ResponsePaginated
     */
    public function lastPage(): ResponsePaginated
    {
        $params = $this->getRequestParametersPaginable();

        $this->failIfCursorPagination($params);

        $total = $this->count();
        $offset = ($params->getOffset() > 0) ? $params->getOffset() : 0;

        // If the offset specified in the request exceeds the last available pagination,
        // the offset of the last pagination is used instead
        if ($offset < $total - $params->getPerPage() || $offset > $total - 1) {
            $offset = $total - $params->getPerPage();
        }

        $params->setOffset($offset);
        $this->request->setParameters($params);

        $response = $this->client->request($this->request);

        return new ResponsePaginated($response);
    }

    /**
     * Returns the next paginated result
     *
     * @return ResponsePaginated
     */
    public function nextPage(): ResponsePaginated
    {
        $params = $this->getRequestParametersPaginable();

        $this->failIfCursorPagination($params);

        $offset = ($params->getOffset() > 0) ? $params->getOffset() : 0;
        $offset = $offset + $params->getPerPage();

        $params->setOffset($offset);

        $this->request->setParameters($params);

        $lastPageResponse = $this->client->request($this->request);

        return new ResponsePaginated($lastPageResponse);
    }

    /**
     * Returns the previous paginated result
     *
     * @return ResponsePaginated
     */
    public function previousPage(): ResponsePaginated
    {
        $params = $this->getRequestParametersPaginable();

        $this->failIfCursorPagination($params);

        $offset = $params->getOffset();
        $offset = $offset - $params->getPerPage();
        if ($offset < 0) {
            $offset = 0;
        }

        $params->setOffset($offset);

        $this->request->setParameters($params);

        $lastPageResponse = $this->client->request($this->request);

        return new ResponsePaginated($lastPageResponse);
    }

    /**
     * Iterates over the items in the collection and passes each item to a callable
     *
     * @param callable $callback
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function each(callable $callback): bool
    {
        return $this->loopApiQuery(function (array $items, int $key, Pagination $pagination, Header $header) use ($callback) {
            foreach ($items as $value) {
                if ($callback($value, $key, $pagination, $header) === false) {
                    return false;
                }
                $key++;
            }
        });
    }

    /**
     * Loops through paginated API endpoints to retrieve all results
     *
     * @param callable $callback
     * @return bool
     */
    protected function loopApiQuery(callable $callback): bool
    {
        $params = $this->request->getParameters();

        if (!$params instanceof PaginationInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Request parameter "%s" must implement "%s".',
                    VarType::getTypeName($params),
                    PaginationInterface::class
                )
            );
        }

        // Si un offset est deja spécifié on démarre la boucle à partir de celle ci, sinon on part de 0
        $offset = ($params->getOffset() > 0) ? $params->getOffset() : 0;
        $params->setOffset($offset);

        $this->request->setParameters($params);

        $total = 0;

        do {
            // Executes a paginated API request.
            // If there are no results, exits the loop; otherwise, calls the provided callable with the API results.
            $response = $this->client->request($this->request);

            $responsePaginated = new ResponsePaginated($response);

            // Header response
            $header = $responsePaginated->getHeader();

            // Body response
            $data = $responsePaginated->getBody();

            // Pagination
            $pagination = $responsePaginated->getPagination();

            // Total count of items matching the query
            $total = $pagination->getTotal();

            // The last request with the "cursor" returns 0 items, and curseur = curseurSuivant
            if ($pagination->issetNextCursor()) {
                if (
                    $pagination->getPerPage() == 0
                    && !empty($pagination->getNextCursor())
                    && $pagination->getNextCursor() == $pagination->getCursor()
                ) {
                    return true;
                }
            }

            // Each result set is passed to the callback with its data
            if ($callback($data, $offset, $pagination, $header) === false) {
                return false;
            }

            // Facet does not use collection
            if ($this->useFacet()) {
                return true;
            }

            // Set pagination
            if ($pagination->issetNextCursor()) {
                $params->setCursor($pagination->getNextCursor());
            } else {
                $offset = $offset + $params->getPerPage();
                $params->setOffset($offset);
            }

            unset($response, $data, $pagination);

            $this->request->setParameters($params);
        } while ($offset < $total);

        return true;
    }
}
