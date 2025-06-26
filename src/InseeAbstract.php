<?php

namespace LuFiipe\InseeSierene;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use LuFiipe\InseeSierene\Authorization\AuthorizationRequestHeaderInterface;
use LuFiipe\InseeSierene\Authorization\InseeApiKey\InseeApiKey;
use LuFiipe\InseeSierene\Exception\InseeClientException;
use LuFiipe\InseeSierene\Middleware\InseeMiddleware;
use LuFiipe\InseeSierene\Request\Request;
use LuFiipe\InseeSierene\Response\Collection;
use LuFiipe\InseeSierene\Response\Response;
use LuFiipe\SimplEvent\Event;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Base class for INSEE APIs
 */
abstract class InseeAbstract
{
    // Name version
    public const CLIENT_VERSION = '1.0.0';
    public const CLIENT_FULL_NAME = 'LuFiipe/InseeSierene v:' . self::CLIENT_VERSION;

    // Default timeout in seconds
    public const TIME_OUT_DEFAULT = 0;

    /**
     * Guzzle Client
     *
     * @var Client
     */
    private Client $client;

    /**
     * Timeout of the request in seconds. Use 0 to wait indefinitely
     *
     * @var float
     */
    private float $timeout;

    /**
     * HTTP Authorization request header
     *
     * @var AuthorizationRequestHeaderInterface
     */
    private AuthorizationRequestHeaderInterface $authorization;

    /**
     * Construct the INSEE Api
     *
     * @param string $apiKey INSEE Sirene API authentication key
     * @param boolean $useRetryOnRateLimit Indicates if the retry on rate limit middleware is used
     * @param float $timeout Timeout of the request in seconds
     * @param callable|null $handler HTTP handler function to use with the Guzzle stack
     */
    public function __construct(string $apiKey = '',  bool $useRetryOnRateLimit = true, float $timeout = self::TIME_OUT_DEFAULT, ?callable $handler = null)
    {
        $this->authorization = new InseeApiKey($apiKey);
        $this->timeout = $timeout;

        // Guzzle handler stack 
        $handlerStack = HandlerStack::create($handler);

        // Register a middleware that retries requests after a rate limit (Http 429 error)
        if ($useRetryOnRateLimit) {
            $handlerStack->push(InseeMiddleware::retryOnRateLimit());
        }

        $this->client = new Client([
            'handler' => $handlerStack,
        ]);
    }

    /**
     * Get the HTTP Authorization request header
     *
     * @return AuthorizationRequestHeaderInterface
     */
    public function getAuthorization(): AuthorizationRequestHeaderInterface
    {
        return $this->authorization;
    }

    /**
     * Set the HTTP Authorization request header
     *
     * @param AuthorizationRequestHeaderInterface $authorization HTTP Authorization request header
     * @return self
     */
    public function setAuthorization(AuthorizationRequestHeaderInterface $authorization): self
    {
        $this->authorization = $authorization;

        return $this;
    }

    /**
     * Timeout of the request in seconds
     *
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->timeout;
    }

    /**
     * Send the API request and return a response object
     *
     * @param Request $request INSEE Sirene request
     * @return Response
     */
    public function requestElement(Request $request): Response
    {
        $response = $this->request($request);

        return new Response($response);
    }

    /**
     * Send the API request and return a collection object
     *
     * @param Request $request INSEE Sirene request
     * @return Collection
     */
    public function requestCollection(Request $request): Collection
    {
        return new Collection($this, $request);
    }

    /**
     * Return the HTTP Authorization request header to use 
     *
     * @return array<string, array<string, string|null>>
     */
    public function getHeaderAuthorization(): array
    {
        return [
            'headers' => $this->getAuthorization()->getHeaderAuthorization()
        ];
    }

    /**
     * Return the HTTP User-Agent request header to use
     */
    public function getHeaderUserAgent(): string
    {
        return self::CLIENT_FULL_NAME;
    }

    /**
     * Make the API call
     *
     * @param Request $request INSEE Sirene request
     * @return PsrResponseInterface
     */
    public function request(Request $request): PsrResponseInterface
    {
        // Emit request event
        Event::emit(Events::REQUESTING, $request);

        // Check la méthode HTTP
        $method = strtolower($request->getMethod());
        switch ($method) {
            case 'head':
            case 'get':
            case 'post':
            case 'put':
            case 'patch':
            case 'delete':
                break;
            default:
                throw new \InvalidArgumentException("Invalid HTTP method: '{$request->getMethod()}'.");
        }

        // API request URL
        $url = $request->getUrl();

        // Request options to apply
        $requestOptions = array_merge_recursive(
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => $this->getHeaderUserAgent(),
                ],
                'timeout' => $this->getTimeout(),
                'connect_timeout' => $this->getTimeout(),
            ],
            $this->getHeaderAuthorization(),
        );

        // Request body
        $requestBody = $request->getRequestBody();

        // Body request option
        if (!empty($requestBody)) {
            $requestOptions['form_params'] = $requestBody;
        }

        // Send an HTTP request.
        try {
            $response = $this->client->{$method}($url, $requestOptions);
        } catch (ClientException $e) {
            throw new InseeClientException($e);
        } catch (ServerException $e) {
            throw new InseeClientException($e);
        }

        return $response;
    }

    /**
     * Check if the request was successful
     *
     * @param integer $status HTTP response status code
     * @return boolean
     */
    protected function isSuccessfulRequest(int $status): bool
    {
        return $status >= 200 && $status < 300;
    }
}
