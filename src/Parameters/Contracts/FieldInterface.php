<?php

namespace LuFiipe\InseeSierene\Parameters\Contracts;

use DateTime;

/**
 * Fields parameter interface
 * 
 */
interface FieldInterface
{
    /**
     * Gets requested fields
     *
     * @return array<string>
     */
    public function getFields(): array;

    /**
     * Sets requested fields
     *
     * @param array<string> $fields
     * @return self
     */
    public function setFields(array $fields): self;

    /**
     * Returns true if hides empty fields
     *
     * @return boolean
     */
    public function getHideNull(): bool;

    /**
     * Set to true to hide empty fields
     *
     * @param boolean $hideNull
     * @return self
     */
    public function setHideNull(bool $hideNull): self;

    /**
     * Converts requested fields into an array
     *
     * @return array<string, string>
     */
    public function fieldableToArray(): array;
}
