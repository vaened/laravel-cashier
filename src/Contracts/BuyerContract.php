<?php
/**
 * Created by enea dhack - 30/05/2017 03:20 PM
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\Arrayable;


/**
 * Interface BuyerContract
 * @package Enea\Cashier\Contracts
 *
 * Represents the buyer
 */
interface BuyerContract extends Arrayable
{

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return int|string
     */
    public function getBuyerKey();

}