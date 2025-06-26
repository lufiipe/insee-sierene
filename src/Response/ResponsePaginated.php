<?php

namespace LuFiipe\InseeSierene\Response;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Paginated response
 */
class ResponsePaginated extends Response
{
    /**
     * Pagination   
     *
     * @var Pagination
     */
    private Pagination $pagination;

    /**
     * Construct the paginated response
     * 
     * @param PsrResponseInterface $response
     */
    public function __construct(PsrResponseInterface $response)
    {
        parent::__construct($response);

        $this->pagination = new Pagination;
        $this->initPaginationFromResponse($response);
    }

    /**
     * Initializes pagination from the Sirene API response
     *
     * @param PsrResponseInterface $response
     * @return void
     */
    private function initPaginationFromResponse(PsrResponseInterface $response): void
    {
        $body = json_decode($response->getBody(), true);
        $headerAttributes = [];

        if (is_array($body) && isset($body['header']) && is_array($body['header'])) {
            $headerAttributes = $body['header'];
        }

        $this->setPagination(new Pagination($headerAttributes));
    }

    /**
     * Sets pagination
     *
     * @param Pagination $pagination
     * @return self
     */
    private function setPagination(Pagination $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Gets pagination
     *
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }
}
