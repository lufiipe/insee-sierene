<?php

namespace LuFiipe\InseeSierene\Parameters\Contracts;

/**
 * Advanced query parameter interface
 */
interface QueryInterface
{
    /**
     * Gets the advanced query
     *
     * @return string|null
     */
    public function getQuery(): ?string;

    /**
     * Sets the advanced query
     *
     * @param string $query
     * @return self
     */
    public function setQuery(string $query): self;

    /**
     * Converts advanced query into an array
     *
     * @return array<string, string>
     */
    public function queryableToArray(): array;
}
