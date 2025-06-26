<?php

namespace LuFiipe\InseeSierene\Parameters\Traits;

/**
 * Trait for advanced query parameter
 */
trait QueryableTrait
{
    /**
     * Advanced query content
     *
     * @var string|null
     */
    private ?string $query = null;

    /**
     * Gets the advanced query
     *
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Sets the advanced query
     *
     * @param string $query
     * @return self
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Converts advanced query into an array
     *
     * @return array<string, string>
     */
    public function queryableToArray(): array
    {
        $requestBody = [];

        if ($this->query) {
            $requestBody['q'] = $this->query;
        }

        return $requestBody;
    }
}
