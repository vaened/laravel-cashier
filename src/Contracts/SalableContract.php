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
    public function getItemKey( );

    /**
     * Returns item name
     *
     * @return string
     * */
    public function getFullName( ): ?string;

    /**
     * Returns the unit of measure of the item
     *
     * @return string
     * */
    public function getMeasure(): ?string;

    /**
     * Get base price for item
     *
     * @return float
     */
    public function getBasePrice( ): float;

}