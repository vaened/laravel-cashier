<?php
/**
 * Created by enea dhack - 29/05/2017 04:20 PM
 */

namespace Enea\Cashier\Contracts;

interface SalableContract extends CartElementContract
{

    /**
     * Returns true in case of being subject to tax
     *
     * @return bool
     */
    public function isTaxable();

}