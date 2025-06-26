<?php

namespace LuFiipe\InseeSierene\Parameters;

use LuFiipe\InseeSierene\Parameters\Contracts\PaginationInterface;

/**
 * Parameters to use in a HTTP message body with collection-based requests
 */
interface PaginatedSearchInterface extends ParametersInterface, PaginationInterface {}
