<?php
/**
 * Created by enea dhack - 29/05/2017 04:19 PM
 */

namespace Enea\Cashier\Contracts;


/**
 * The classes that implement this interface must establish the quantity
 * for the article, it can not be changed under any circumstances
 *
 * @package Enea\Cashier\Contracts
 */
interface CountableStaticContract
{

    /**
     * Get item quantity
     *
     * @return int
     * */
    public function getQuantity( ): int;

}