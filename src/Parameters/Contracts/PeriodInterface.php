<?php

namespace LuFiipe\InseeSierene\Parameters\Contracts;

use DateTime;

/**
 * Date parameter interface
 */
interface PeriodInterface
{
    /**
     * Gets the date parameter
     *
     * @return DateTime|null
     */
    public function getDate(): ?DateTime;

    /**
     * Sets the date parameter
     *
     * @param DateTime $date
     * @return self
     */
    public function setDate(DateTime $date): self;

    /**
     * Converts date parameter into an array
     *
     * @return array<string, string>
     */
    public function periodableToArray(): array;
}
