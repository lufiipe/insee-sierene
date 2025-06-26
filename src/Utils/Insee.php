<?php

namespace LuFiipe\InseeSierene\Utils;

/**
 * Insee utils
 */
final class Insee
{
    /**
     * Returns true if the provided string is a valid SIREN code
     *
     * @param string $siren SIREN code
     * @return boolean
     */
    public static function isValidSiren(string $siren): bool
    {
        return self::validate($siren, 9);
    }

    /**
     * Returns true if the provided string is a valid SIRET code
     *
     * @param string $siret SIRET code
     * @return boolean
     */
    public static function isValidSiret(string $siret): bool
    {
        return self::validate($siret, 14);
    }

    /**
     * Returns true if the provided string is a valid SIREN or SIRET code
     *
     * @param string $code SIREN or SIRET code
     * @param integer $length SIREN codes have 9 digits, SIRET codes have 14 digits
     * @return boolean
     */
    private static function validate(string $code, int $length = 9): bool
    {
        if (!is_numeric($code)) {
            return false;
        }

        if (strlen($code) != $length) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < $length; ++$i) {
            $ind = ($length - $i);
            $tmp = (2 - ($ind % 2)) * (int) $code[$i];
            if ($tmp >= 10) {
                $tmp -= 9;
            }
            $sum += $tmp;
        }

        $res = ($sum % 10) == 0;

        if (!$res) {
            /**
             * Exceptions for "La Poste" group
             * @see https://fr.wikipedia.org/wiki/SIRET#Calcul_et_validit%C3%A9_d'un_num%C3%A9ro_SIRET
             */
            $laPosteSiren = '356000000';
            if (($length === 14) && strpos($code, $laPosteSiren) === 0) {
                $res = $laPosteSiren === (string) $code ? true : 0 === array_sum(str_split($code)) % 5;
            }
        }

        return $res;
    }
}
