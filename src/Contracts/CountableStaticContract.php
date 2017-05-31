<?php
/**
 * Created by enea dhack - 29/05/2017 04:19 PM
 */

namespace Enea\Cashier\Contracts;


interface CountableStaticContract
{

    /**
     * Get item quantity
     *
     * @return int
     * */
    public function quantityAttribute( ): int;

}