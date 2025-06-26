<?php

namespace LuFiipe\InseeSierene\Parameters\Contracts;

use LuFiipe\InseeSierene\Parameters\Facet;

/**
 * Facet parameter interface
 */
interface FacetInterface
{
    /**
     * Gets facet
     *
     * @return Facet|null
     */
    public function getFacet(): ?Facet;

    /**
     * Sets facet
     *
     * @param Facet $facet
     * @return self
     */
    public function setFacet(Facet $facet): self;

    /**
     * Converts facet parameters into an array
     *
     * @return array<string, mixed>
     */
    public function facetableToArray(): array;
}
