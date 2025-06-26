<?php

namespace LuFiipe\InseeSierene\Response;

/**
 * Header
 */
class Header
{
    /**
     * HTTP response status code
     *
     * @var integer
     */
    private int $status = 0;

    /**
     * Error message
     *
     * @var string
     */
    private string $message = '';

    /**
     * Construct the header
     * 
     * @param array<mixed> $attributes Attributes of header from the Sirene API
     */
    public function __construct(array $attributes = [])
    {
        if (isset($attributes['statut'])) {
            $this->setStatus($attributes['statut']);
        }

        if (isset($attributes['message'])) {
            $this->setMessage($attributes['message']);
        }
    }

    /**
     * Gets the status code
     *
     * @return integer
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the status code
     *
     * @param mixed $status
     * @return self
     */
    private function setStatus($status): self
    {
        $this->status = is_numeric($status) ? (int) $status : 0;
        return $this;
    }

    /**
     * Gets the error message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Sets the error message
     *
     * @param mixed $message
     * @return self
     */
    private function setMessage($message): self
    {
        $this->message = is_string($message) ? (string) $message : '';
        return $this;
    }
}
