<?php

namespace LuFiipe\InseeSierene\Utils;

/**
 * Type utils
 */
final class VarType
{
    /**
     * Returns the type name of a given value.
     * 
     * If the value is an object, it returns the class name.
     *
     * @param mixed $value The variable being type checked
     * @return string
     */
    public static function getTypeName($value): string
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }
}
