<?php

namespace LuFiipe\InseeSierene\Exception;

use Exception;
use GuzzleHttp\Exception\ClientException;
use SimpleXMLElement;
use Throwable;

/**
 * Base class for all Sirene exceptions
 */
class SireneException extends Exception
{
    /**
     * Construct the exception
     *
     * @param string|ClientException $exception The Exception message to throw
     * @param integer $code The Exception code
     * @param Throwable|null $previous The previous exception used for the exception chaining
     */
    public function __construct($exception = '', int $code = 400, ?Throwable $previous = null)
    {
        if ($exception instanceof ClientException) {
            $message = $this->getErrorDescription($exception);
            $code = $exception->getCode();
            $previous = $exception;
        } elseif (is_string($exception)) {
            $message = $exception;
        } else {
            throw new \TypeError(sprintf(
                'Argument 1 passed to %s::__construct() must be of type string or \Throwable, %s given.',
                __CLASS__,
                gettype($exception)
            ));
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Retrieves a description of the error based on the response content
     *
     * @param ClientException $responseException
     * @return string
     */
    protected function getErrorDescription(ClientException $responseException): string
    {
        $message = '';

        $response = $responseException->getResponse();
        $contentType = implode(';', $response->getHeader('Content-Type'));
        $body = $response->getBody()->getContents();

        if (stripos($contentType, 'xml') !== false) {
            try {
                $xmlElement = new SimpleXMLElement($body);
                $xmlNode = $xmlElement->children('am', true);

                $message = isset($xmlNode->message) ? (string) $xmlNode->message : '';
                $description = isset($xmlNode->description) ? (string) $xmlNode->description : '';

                if (!empty($description)) {
                    $message .= '. ' . $description;
                }
            } catch (\Exception $e) {
                $message = 'Error processing XML: ' . $e->getMessage();
            }
        } elseif (stripos($contentType, 'json') !== false) {
            $decoded = json_decode($body, true);

            if (is_array($decoded)) {
                if (isset($decoded['error_description']) && is_string($decoded['error_description'])) {
                    $message = $decoded['error_description'];
                }

                if (empty($message)) {
                    if (isset($decoded['header']) && is_array($decoded['header'])) {
                        if (isset($decoded['header']['message']) && is_string($decoded['header']['message'])) {
                            $message = $decoded['header']['message'];
                        }
                    }
                }

                if (empty($message)) {
                    if (isset($decoded['fault']) && is_array($decoded['fault'])) {
                        $fault = $decoded['fault'];
                        if (isset($fault['message']) && is_string($fault['message'])) {
                            $message = $fault['message'];
                        }
                        if (isset($fault['description']) && is_string($fault['description'])) {
                            $description = $fault['description'];
                        }
                    }
                    if (!empty($description)) {
                        $message .= '. ' . $description;
                    }
                }
            }
        } else {
            $message = 'The response body does not contain valid JSON data or is empty.';
        }

        if (empty($message)) {
            $message = $responseException->getMessage() ?: 'An unknown error occurred.';
        }

        return $message;
    }
}
