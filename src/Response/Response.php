<?php

namespace LuFiipe\InseeSierene\Response;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Insee Siren response
 */
class Response
{
    // Property name that holds the facet data in the response
    const RESPONSE_DATA_ITEM_FACETS = 'facettes';

    /**
     * Header response
     *
     * @var Header
     */
    private Header $header;

    /**
     * Body Response
     *
     * @var array<mixed>
     */
    private $body = [];

    /**
     * Construct the Siren response
     * 
     * @param PsrResponseInterface|null $response
     */
    public function __construct(?PsrResponseInterface $response = null)
    {
        $this->header = new Header();
        $this->body = [];

        if ($response) {
            $this->initHeaderFromResponse($response);
            $this->initBodyFromResponse($response);
        }
    }

    /**
     * Initializes header from the Sirene API response
     *
     * @param PsrResponseInterface $response
     * @return void
     */
    protected function initHeaderFromResponse(PsrResponseInterface $response): void
    {
        $body = $response->getBody()->getContents();

        $headerAttributes = [];
        $decoded = json_decode($body, true);
        if (is_array($decoded) && isset($decoded['header']) && is_array($decoded['header'])) {
            $headerAttributes = $decoded['header'];
        } else {
            $headerAttributes = [
                'statut' => $response->getStatusCode(),
                'message' => $response->getReasonPhrase(),
            ];
        }

        $this->header = new Header($headerAttributes);
    }

    /**
     * Initializes header from the Sirene API response
     *
     * @param PsrResponseInterface $response
     * @return void
     */
    protected function initBodyFromResponse(PsrResponseInterface $response): void
    {
        $decoded = json_decode($response->getBody(), true);

        if (!is_array($decoded)) {
            $decoded = [];
        }

        // Removes the response header if present
        unset($decoded['header']);

        // In case the response includes facets
        if ($this->haveFacetResponse($decoded)) {
            $this->setBody($decoded[Response::RESPONSE_DATA_ITEM_FACETS]);
            return;
        }

        if (count($decoded) == 1) {
            $this->setBody(reset($decoded));
        } else {
            $this->setBody($decoded);
        }
    }

    /**
     * Sets the header response
     *
     * @param Header $header
     * @return self
     */
    protected function setHeader(Header $header): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Gets the header response
     *
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * Sets the body response
     *
     * @param mixed $body
     * @return self
     */
    protected function setBody($body): self
    {
        $this->body = is_array($body) ? $body : [];

        return $this;
    }

    /**
     * Response::getBody() alias
     *
     * @return array<mixed>
     */
    public function get(): array
    {
        return $this->getBody();
    }

    /**
     * Gets the body response
     *
     * @return array<mixed>
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Return true if have facet in response
     *
     * @param array<mixed> $data
     * @return boolean
     */
    protected function haveFacetResponse(array $data): bool
    {
        return array_key_exists(Response::RESPONSE_DATA_ITEM_FACETS, $data);
    }
}
