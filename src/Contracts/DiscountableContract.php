<?php
/**
 * Created by enea dhack - 30/05/2017 06:03 PM.
 */

namespace Enea\Cashier\Contracts;

use Enea\Cashier\Modifiers\DiscountContract;

/**
 * When implementing this interface, it is possible to assign a discount on an item.
 */
interface DiscountableContract
{
    /**
     * Get the item discount in percentage.
     *
     * @return DiscountContract|null
     */
    public function getDiscounts();
}
