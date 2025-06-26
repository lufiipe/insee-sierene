<?php

namespace LuFiipe\InseeSierene\Parameters\Traits;

use DateTime;

/**
 * Trait for date parameter
 */
trait PeriodableTrait
{
    /**
     * Date where the search criteria will be applied to the historical fields
     *
     * @var DateTime|null
     */
    private ?DateTime $date = null;

    /**
     * Gets the date parameter
     *
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * Sets the date parameter
     *
     * @param DateTime $date
     * @return self
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Converts date parameter into an array
     *
     * @return array<string, string>
     */
    public function periodableToArray(): array
    {
        $requestBody = [];

        if ($this->getDate() instanceof DateTime) {
            $requestBody['date'] = $this->getDate()->format('Y-m-d');
        }

        return $requestBody;
    }
}
