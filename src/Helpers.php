<?php
/**
 * Created by enea dhack - 15/09/2017 02:12 PM.
 */

namespace Enea\Cashier;

class Helpers
{
    /**
     * format decimal.
     *
     * @param float $value
     *
     * @return float
     */
    public static function decimalFormat($value)
    {
        return round($value, config('cashier.decimals', 3));
    }

    /**
     * Returns the percentage value.
     *
     * @param int $percentage
     * @return float
     */
    public static function toPercentage($percentage)
    {
        if (! is_float($percentage)) {
            return $percentage / 100;
        }

        return $percentage;
    }
}
