<?php
/**
 * Created by enea dhack - 30/05/2017 08:05 PM.
 */

namespace Enea\Cashier\Contracts;

interface SoldItemContract extends CartElementContract
{
    /**
     * Get item quantity.
     *
     * @return int
     * */
    public function getQuantity();
}
