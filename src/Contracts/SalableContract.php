<?php
/**
 * Created by enea dhack - 29/05/2017 04:20 PM
 */

namespace Enea\Cashier\Contracts;

interface SalableContract
{

    /**
     * Key that identifies the article as unique
     *
     * @return int|string
     */
    public function getItemKey( );

    /**
     * Returns item name
     *
     * @return string
     * */
    public function getShortDescription( ): ?string;

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

    /**
     * Returns an array with extra properties
     *
     * @return array
     * */
    public function getCustomProperties( ): array;

}