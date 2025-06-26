<?php

namespace LuFiipe\InseeSierene\Parameters;

/**
 * Parameters to use in a HTTP message body
 */
interface ParametersInterface
{
    /**
     * Returns the parameters to use in a HTTP message body as an array
     *
     * @return array<mixed>
     */
    public function toRequestBody(): array;
}
