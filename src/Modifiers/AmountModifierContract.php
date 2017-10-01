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
     * Returns the percentage in integer format to be taken from the amount.
     *
     * @return int
     */
    public function getPercentage();
}