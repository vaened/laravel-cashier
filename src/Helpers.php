<?php
/**
 * Created by enea dhack - 15/09/2017 02:12 PM.
 */

namespace Enea\Cashier;

use Illuminate\Contracts\Support\Arrayable;

class Helpers
{
    public static function decimal(float $decimal): float
    {
        return round($decimal, config('cashier.decimals', 3));
    }

    public static function convertToArray(array $values): array
    {
        return array_map(fn(Arrayable $arrayable) => $arrayable->toArray(), $values);
    }
}
