<?php
/**
 * Created by enea dhack - 05/10/2017.
 */

namespace Enea\Cashier\Contracts;

interface CalculableContract
{
    /**
     * Get base price for item.
     *
     * @return float
     */
    public function getBasePrice();
}