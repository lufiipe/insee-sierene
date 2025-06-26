<?php

namespace LuFiipe\InseeSierene\Parameters\Contracts;

/**
 * Pagination parameter interface
 */
interface PaginationInterface
{
    // Default number of items requested in the response
    public const PER_PAGE_DEFAULT = 20;

    /**
     * Gets the number of items per page
     *
     * @return integer
     */
    public function getPerPage(): int;

    /**
     * Sets the number of items per page
     *
     * @param integer $perPage
     * @return self
     */
    public function setPerPage(int $perPage): self;

    /**
     * Gets the pagination offset
     *
     * @return integer|null
     */
    public function getOffset(): ?int;

    /**
     * Sets the pagination offset
     *
     * @param integer $offset
     * @return self
     */
    public function setOffset(int $offset): self;

    /**
     * Gets the cursor
     *
     * @return string
     */
    public function getCursor(): string;

    /**
     * Sets the cursor
     *
     * @param string $cursor
     * @return self
     */
    public function setCursor(string $cursor): self;

    /**
     * Clear the cursor
     *
     * @return self
     */
    public function clearCursor(): self;

    /**
     * Enable deep pagination
     *
     * @return self
     */
    public function withCursor(): self;

    /**
     * Returns true if the cursor is enabled
     *
     * @return boolean
     */
    public function isCursorEnabled(): bool;

    /**
     * Converts pagination parameters into an array
     *
     * @return array<string, int|string|null>
     */
    public function paginableToArray(): array;
}
