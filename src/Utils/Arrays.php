<?php

namespace LuFiipe\InseeSierene\Utils;

/**
 * Arrays utils
 */
final class Arrays
{
    /**
     * Removes empty values from an array
     *
     * @param array<mixed> $array Array to filter
     * @return array<mixed>
     */
    public static function removeEmptyElements(array $array): array
    {
        return array_filter($array, function ($value) {
            return !empty($value);
        });
    }

    /**
     * Sort all elements of type string by splitting them using a specified delimiter
     * <code>
     *  sortElementsWithSeparator(['c,a,2,b,1', '9,4,0,8'], ',');
     *  // return: ['1,2,a,b,c', '0,4,8,9'];
     * </code>
     * 
     * @param array<mixed> $array Array to sort
     * @param string $separator
     * @return array<mixed>
     */
    public static function sortElementsWithSeparator(array $array, string $separator): array
    {
        if ($separator === '') {
            throw new \InvalidArgumentException('Separator must be a non-empty string.');
        }

        foreach ($array as &$value) {
            if (is_string($value)) {
                if (strpos($value, $separator) !== false) {
                    $newVal = (array) explode($separator, $value);
                    sort($newVal);
                    $value = implode($separator, $newVal);
                }
            }
        }
        unset($value);

        return $array;
    }
}
