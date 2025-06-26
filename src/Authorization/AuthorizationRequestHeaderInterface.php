<?php

namespace LuFiipe\InseeSierene\Authorization;

/**
 * HTTP Authorization request header interface
 */
interface AuthorizationRequestHeaderInterface
{
    /**
     * Return the HTTP Authorization request header
     *
     * @return array<string, ?string>
     */
    public function getHeaderAuthorization(): array;
}
