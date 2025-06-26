<?php

namespace LuFiipe\InseeSierene\Parameters\Contracts;

/**
 * Sort parameter interface
 */
interface SortInterface
{
    public const SORT_ORDER_ASC = 'asc';
    public const SORT_ORDER_DESC = 'desc';
    public const SORT_SIRET_ETABLISSEMENT_PREDECESSEUR = '';
    public const SORT_SIRET_ETABLISSEMENT_SUCCESSEUR = 'successeur';

    /**
     * Gets the fields on which sorting will be performed.
     * Each array entry is a key-value pair where the key is the field name and the value defines the sort order.
     *
     * @return array<mixed>
     */
    public function getSorts(): array;

    /**
     * Sets the fields on which sorting will be performed.
     * Each array entry is a key-value pair where the key is the field name and the value defines the sort order
     *
     * @param array<mixed> $sorts
     * @return self
     */
    public function setSorts(array $sorts): self;

    /**
     * Add a sort
     *
     * @param string $field Field name
     * @param string $order Sort order
     * @return self
     */
    public function addSort($field, $order = SortInterface::SORT_ORDER_ASC): self;

    /**
     * Converts sorting parameters into an array
     *
     * @return array<string, string>
     */
    public function sortableToArray(): array;
}
