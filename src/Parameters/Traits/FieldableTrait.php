<?php

namespace LuFiipe\InseeSierene\Parameters\Traits;

/**
 * Trait fields parameter
 */
trait FieldableTrait
{
    /**
     * List of requested fields
     *
     * @var array<string>
     */
    private array $fields = [];

    /**
     * Hides (true) or shows (false, default) fields that have no value
     *
     * @var boolean
     */
    private bool $hideNull = false;

    /**
     * Gets requested fields
     *
     * @return array<string>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Sets requested fields
     *
     * @param array<string> $fields
     * @return self
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Returns true if hides empty fields
     *
     * @return boolean
     */
    public function getHideNull(): bool
    {
        return $this->hideNull;
    }

    /**
     * Set to true to hide empty fields
     *
     * @param boolean $hideNull
     * @return self
     */
    public function setHideNull(bool $hideNull): self
    {
        $this->hideNull = $hideNull;

        return $this;
    }

    /**
     * Converts requested fields into an array
     *
     * @return array<string, string>
     */
    public function fieldableToArray(): array
    {
        $requestBody = [];

        if (!empty($this->getFields())) {
            $requestBody['champs'] = implode(',', $this->getFields());
        }

        if ($this->getHideNull()) {
            $requestBody['masquerValeursNulles'] = 'true';
        }

        return $requestBody;
    }
}
