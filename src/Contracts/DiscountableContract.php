<?php
/**
 * Created by enea dhack - 30/05/2017 06:03 PM
 */

namespace Enea\Cashier\Contracts;


interface DiscountableContract
{

    /**
     * Get the item discount in percentage
     *
     * @return int
     */
    public function getDiscountPercentageAttribute( );

}