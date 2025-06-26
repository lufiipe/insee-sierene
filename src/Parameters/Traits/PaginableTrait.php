<?php

namespace LuFiipe\InseeSierene\Parameters\Traits;

use LuFiipe\InseeSierene\Parameters\Contracts\PaginationInterface;

/**
 * Trait for pagination parameter
 */
trait PaginableTrait
{
    /**
     * Number of items per page
     *
     * @var integer
     */
    private int $perPage = PaginationInterface::PER_PAGE_DEFAULT;

    /**
     * Starting point from where the data should be fetched
     *
     * @var integer|null
     */
    private ?int $offset = null;

    /**
     * Parameter used for deep pagination
     *
     * @var string
     */
    private string $cursor = '';

    /**
     * Gets the number of items per page
     *
     * @return integer
     */
    public function getPerPage(): int
    {
        $perPage = $this->perPage;

        if ($perPage < 0) {
            $perPage = PaginationInterface::PER_PAGE_DEFAULT;
        }

        return $perPage;
    }

    /**
     * Sets the number of items per page
     *
     * @param integer $perPage
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        if ($perPage < 0) {
            $perPage = PaginationInterface::PER_PAGE_DEFAULT;
        }

        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Gets the pagination offset
     *
     * @return integer|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * Sets the pagination offset
     *
     * @param integer $offset
     * @return self
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Gets the cursor
     *
     * @return string
     */
    public function getCursor(): string
    {
        return $this->cursor;
    }

    /**
     * Sets the cursor
     *
     * @param string $cursor
     * @return self
     */
    public function setCursor(string $cursor): self
    {
        $this->cursor = $cursor;

        return $this;
    }

    /**
     * Clear the cursor
     *
     * @return self
     */
    public function clearCursor(): self
    {
        $this->cursor = '';

        return $this;
    }

    /**
     * Enable deep pagination
     *
     * @return self
     */
    public function withCursor(): self
    {
        // For your first request, add the parameter curseur=*
        $this->cursor = '*';

        return $this;
    }

    /**
     * Returns true if the cursor is enabled
     *
     * @return boolean
     */
    public function isCursorEnabled(): bool
    {
        return !empty(trim($this->getCursor()));
    }

    /**
     * Converts pagination parameters into an array
     *
     * @return array<string, int|string|null>
     */
    public function paginableToArray(): array
    {
        $requestBody = [];

        $requestBody['nombre'] = $this->getPerPage();

        $offset = $this->getOffset();
        if ($offset < 0) {
            $offset = 0;
        }
        $requestBody['debut'] = $offset;

        if ($this->isCursorEnabled()) {
            $requestBody['curseur'] = $this->getCursor();;
        }

        return $requestBody;
    }
}
