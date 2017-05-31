<?php
/**
 * Created by enea dhack - 29/05/2017 04:20 PM
 */

namespace Enea\Cashier\Contracts;


interface SalableContract
{

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return int|string
     */
    public function getSalableKey( );

    /**
     * Get base price for item
     *
     * @return float
     */
    public function getBasePriceAttribute( ): float;

}