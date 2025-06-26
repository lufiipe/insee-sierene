<?php

namespace LuFiipe\InseeSierene;

/**
 * Contains all events thrown in the InseeSierene component
 */
final class Events
{

    /**
     * The REQUESTING event occurs at the very beginning of request
     */
    public const REQUESTING = 'sirene.requesting';

    /**
     * The RATE_LIMIT_REACHED event occurs when the API rate limit has been exceeded
     */
    public const RATE_LIMIT_REACHED = 'sirene.rate_limit_reached';
}
