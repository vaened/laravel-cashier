<?php
/**
 * Created by enea dhack - 29/09/2017 12:00 PM.
 */

namespace Enea\Cashier\Modifiers;

use Enea\Cashier\Contracts\AttributableContract;

/**
 * Interface DiscountContract.
 *
 * Defined a discount.
 *
 * @package Enea\Cashier\Contracts
 */
interface DiscountContract extends AttributableContract, AmountModifierContract
{
    /**
     * Returns the discount key.
     *
     * @return int|string
     */
    public function getDiscountCode();

    /**
     * Returns discount description.
     *
     * @return string
     */
    public function getDescription();
}
