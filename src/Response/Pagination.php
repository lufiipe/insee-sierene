<?php

namespace LuFiipe\InseeSierene\Response;

/**
 * Offset pagination and cursor pagination
 */
class Pagination
{
    /**
     * Total count of items matching the query
     *
     * @var integer
     */
    private int $total = 0;

    /**
     * Starting point from where the data should be fetched
     *
     * @var integer
     */
    private int $offset = 0;

    /**
     * Number of items per page
     *
     * @var integer
     */
    private int $perPage = 0;

    /**
     * Value of the cursor where the data should be fetched.
     * Add the parameter "cursor=*" to your first query.
     *
     * @var string
     */
    private string $cursor = '';

    /**
     * Value of the cursor to fetch the next page of results
     *
     * @var string
     */
    private string $nextCursor = '';

    /**
     * Construct the pagination
     * 
     * @param array<mixed> $attributes Attributes of pagination from the Sirene API
     */
    public function __construct(array $attributes = [])
    {
        if (isset($attributes['total'])) {
            $this->setTotal($attributes['total']);
        }

        if (isset($attributes['debut'])) {
            $this->setOffset($attributes['debut']);
        }

        if (isset($attributes['nombre'])) {
            $this->setPerPage($attributes['nombre']);
        }

        if (isset($attributes['curseur'])) {
            $this->setCursor($attributes['curseur']);
        }

        if (isset($attributes['curseurSuivant'])) {
            $this->setNextCursor($attributes['curseurSuivant']);
        }
    }

    /**
     * Gets the total number of items
     *
     * @return integer
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Sets the total number of items
     *
     * @param mixed $total
     * @return self
     */
    public function setTotal($total): self
    {
        $this->total = is_numeric($total) ? (int) $total : 0;
        return $this;
    }

    /**
     * Gets the pagination offset
     *
     * @return integer
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Sets the pagination offset
     *
     * @param mixed $offset
     * @return self
     */
    public function setOffset($offset): self
    {
        $this->offset = is_numeric($offset) ? (int) $offset : 0;
        return $this;
    }

    /**
     * Gets the number of items per page
     *
     * @return integer
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Sets the number of items per page
     *
     * @param mixed $perPage
     * @return self
     */
    public function setPerPage($perPage): self
    {
        $this->perPage = is_numeric($perPage) ? (int) $perPage : 0;
        return $this;
    }

    /**
     * Gets the current cursor
     *
     * @return string
     */
    public function getCursor(): string
    {
        return $this->cursor;
    }

    /**
     * Sets the current cursor
     *
     * @param mixed $cursor
     * @return self
     */
    public function setCursor($cursor): self
    {
        $this->cursor = is_string($cursor) ? (string) $cursor : '';
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
     * Gets the value of the cursor to fetch the next page of results
     *
     * @return string
     */
    public function getNextCursor(): string
    {
        return $this->nextCursor;
    }

    /**
     * Sets the value of the cursor to fetch the next page of results
     *
     * @param mixed $nextCursor
     * @return self
     */
    public function setNextCursor($nextCursor): self
    {
        $this->nextCursor = is_string($nextCursor) ? (string) $nextCursor : '';
        return $this;
    }

    /**
     * Returns true if the cursor to fetch the next page is set
     *
     * @return boolean
     */
    public function issetNextCursor(): bool
    {
        return !empty(trim($this->getNextCursor()));
    }
}
