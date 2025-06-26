<?php

namespace LuFiipe\InseeSierene\Parameters;

use LuFiipe\InseeSierene\Parameters\Contracts\FieldInterface;
use LuFiipe\InseeSierene\Parameters\Contracts\PeriodInterface;
use LuFiipe\InseeSierene\Parameters\Traits\FieldableTrait;
use LuFiipe\InseeSierene\Parameters\Traits\PeriodableTrait;

/**
 * Unit parameters
 */
class UnitParameters implements ParametersInterface, PeriodInterface, FieldInterface
{
    use PeriodableTrait;
    use FieldableTrait;

    /**
     * Returns the legal entity parameters to use in a HTTP message body as an array
     *
     * @return array<mixed>
     */
    public function toRequestBody(): array
    {
        $requestBody = [];

        // Merge PeriodableTrait
        $requestBody += $this->periodableToArray();

        // Merge FieldableTrait
        $requestBody += $this->fieldableToArray();

        return $requestBody;
    }
}
