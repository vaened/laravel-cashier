<?php
/**
 * Created on 30/09/17 by enea dhack.
 */

namespace Enea\Cashier\Modifiers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface AmountModifierContract extends Arrayable, Jsonable
{
    /**
     * Returns the value that modifies the amount.
     * if it is a percentage value, it must be an integer.
     *
     * @return float
     */
    public function getModifierValue();

    /**
     * Returns true in case the discount is percentage.
     *
     * @return bool
     */
    public function isPercentage();
}
