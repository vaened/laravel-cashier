<?php
/**
 * Created by enea dhack - 15/09/2017 02:12 PM
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
}
