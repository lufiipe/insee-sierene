<?php

namespace LuFiipe\InseeSierene\Parameters\Traits;

use LuFiipe\InseeSierene\Parameters\Facet;

/**
 * Trait providing facet support for advanced search parameter
 */
trait FacetableTrait
{
    /**
     * Facet for advanced search
     *
     * @var Facet|null
     */
    private ?Facet $facet = null;

    /**
     * Gets facet
     *
     * @return Facet|null
     */
    public function getFacet(): ?Facet
    {
        return $this->facet;
    }

    /**
     * Sets facet
     *
     * @param Facet $facet
     * @return self
     */
    public function setFacet(Facet $facet): self
    {
        $this->facet = $facet;

        return $this;
    }

    /**
     * Converts facet parameters into an array
     *
     * @return array<string, mixed>
     */
    public function facetableToArray(): array
    {
        $requestBody = [];

        if ($this->getFacet()) {
            $requestBody += $this->getFacet()->toArray();
        }

        return $requestBody;
    }
}
