<?php
/**
 * Created by enea dhack - 30/05/2017 08:05 PM
 */

namespace Enea\Cashier\Contracts;


interface SoldItemContract
{

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return int|string
     */
    public function getItemKey();

    /**
     * Get item quantity
     *
     * @return int
     * */
    public function getQuantity( ): int;

}