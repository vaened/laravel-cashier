<?php
/**
 * Created by enea dhack - 17/06/17 03:52 PM
 */

namespace Enea\Cashier\Contracts;


interface CartElementContract
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