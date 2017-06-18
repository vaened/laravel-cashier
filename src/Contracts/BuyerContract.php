<?php
/**
 * Created by enea dhack - 30/05/2017 03:20 PM
 */

namespace Enea\Cashier\Contracts;


/**
 * Interface BuyerContract
 * @package Enea\Cashier\Contracts
 *
 * Represents the buyer
 */
interface BuyerContract
{

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return int|string
     */
    public function getBuyerKey();

    /**
     * Returns an array with extra properties
     *
     * @return array
     * */
    public function getCustomProperties( ): array;

}