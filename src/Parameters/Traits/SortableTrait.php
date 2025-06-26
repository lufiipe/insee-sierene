<?php

namespace LuFiipe\InseeSierene\Parameters\Traits;

use LuFiipe\InseeSierene\Parameters\Contracts\SortInterface;

/**
 * Trait for sort parameter
 */
trait SortableTrait
{
    /**
     * Fields on which sorting will be performed
     *
     * @var array<mixed>
     */
    private array $sorts = [];

    /**
     * Gets the fields on which sorting will be performed
     *
     * @return array<mixed>
     */
    public function getSorts(): array
    {
        return $this->sorts;
    }

    /**
     * Sets the fields on which sorting will be performed
     *
     * @param array<mixed> $sorts 
     * @return self
     */
    public function setSorts(array $sorts): self
    {
        if (empty($sorts)) {
            $this->sorts = [];
            return $this;
        }

        foreach ($sorts as $key => $value) {
            if (is_int($key) && !empty($value)) {
                $this->addSort($value);
            } else {
                $this->addSort($key, $value);
            }
        }

        return $this;
    }

    /**
     * Add a sort
     *
     * @param mixed $field Field name
     * @param mixed $order Sort order
     * @return self
     */
    public function addSort($field, $order = SortInterface::SORT_ORDER_ASC): self
    {
        $field = is_string($field) ? (string) $field : '';
        $order = is_string($order) ? (string) $order : SortInterface::SORT_ORDER_ASC;
        if (empty($field)) {
            return $this;
        }

        // Sorting of succession links
        if (strcasecmp($field, SortInterface::SORT_SIRET_ETABLISSEMENT_SUCCESSEUR) == 0) {
            $this->sorts = [SortInterface::SORT_SIRET_ETABLISSEMENT_SUCCESSEUR];

            return $this;
        }

        // Basic Sorting
        switch ($order) {
            case SortInterface::SORT_ORDER_ASC:
            case SortInterface::SORT_ORDER_DESC:
                break;
            default:
                $order = SortInterface::SORT_ORDER_ASC;
        }
        $this->sorts[] = $field . ' ' . $order;

        return $this;
    }

    /**
     * Converts sorting parameters into an array
     *
     * @return array<string, string>
     */
    public function sortableToArray(): array
    {
        $requestBody = [];

        if (!empty($this->getSorts())) {
            $requestBody['tri'] = implode(',', $this->getSorts());
        }

        return $requestBody;
    }
}
