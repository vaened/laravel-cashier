<?php
/**
 * Created by enea dhack - 16/06/17 10:40 PM.
 */

namespace Enea\Cashier\Contracts;

interface AccountElementContract extends SalableContract
{
    /**
     * Get item quantity.
     *
     * @return int
     * */
    public function getQuantity();
}
