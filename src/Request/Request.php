<?php

namespace LuFiipe\InseeSierene\Request;

use GuzzleHttp\Psr7\Query;
use LuFiipe\InseeSierene\Parameters\ParametersInterface;

/**
 * INSEE Sirene request
 */
class Request
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    /**
     * HTTP method
     *
     * @var string
     */
    private $method;

    /**
     * Base URL
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Endpoint
     *
     * @var string
     */
    private $endPoint;

    /**
     * Parameters to use in a HTTP message body
     *
     * @var ParametersInterface|null
     */
    private ?ParametersInterface $parameters = null;

    /**
     * Construct the Sirene request
     * 
     * @param string $method HTTP method
     * @param string $baseUrl Base URL
     * @param string $endPoint Endpoint
     * @param ParametersInterface|null $parameters HTTP message body
     */
    public function __construct(string $method, string $baseUrl, string $endPoint, ?ParametersInterface $parameters = null)
    {
        $this->method = $method;
        $this->baseUrl = $baseUrl;
        $this->endPoint = $endPoint;
        $this->parameters = $parameters;
    }

    /**
     * Generates the full API request URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        // Joins two paths together
        $url = rtrim($this->getBaseUrl(), '/') . '/' . ltrim($this->getEndPoint(), '/');

        $queryString = '';
        if ($this->getMethod() == self::METHOD_GET) {
            $parameters = $this->getParameters();
            $queryParams = $parameters ? $parameters->toRequestBody() : null;
            if ($queryParams) {
                $queryString = Query::build($queryParams);
            }
        }

        // Creates and returns the URL
        return $url . ('' !== $queryString ? '?' . $queryString : '');
    }

    /**
     * Gets the HTTP method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Sets the HTTP method
     *
     * @param string $method
     * @return self
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Gets the base URL
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Sets the base URL
     *
     * @param string $baseUrl
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Gets the endpoint
     *
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * Sets the endpoint
     *
     * @param string $endPoint
     * @return self
     */
    public function setEndPoint(string $endPoint): self
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    /**
     * Gets the HTTP body parameters
     *
     * @return ParametersInterface|null
     */
    public function getParameters(): ?ParametersInterface
    {
        return $this->parameters;
    }

    /**
     * Sets the HTTP body parameters
     *
     * @param ParametersInterface $parameters
     * @return self
     */
    public function setParameters(ParametersInterface $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Returns the parameters to use in a HTTP message body as an array
     *
     * @return array<mixed>
     */
    public function getRequestBody(): array
    {
        if (in_array($this->getMethod(), [self::METHOD_POST])) {
            $parameters = $this->getParameters();

            if ($parameters !== null) {
                return $parameters->toRequestBody();
            }
        }

        return [];
    }
}
