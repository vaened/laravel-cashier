<?php
/**
 * Created by enea dhack - 30/05/2017 03:20 PM
 */

namespace Enea\Cashier\Contracts;


/**
 * Makes the model a potential buyer.
 *
 * @package Enea\Cashier\Contracts
 */
interface BuyerContract
{

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return int|string
     */
    public function getBuyerKey();


}