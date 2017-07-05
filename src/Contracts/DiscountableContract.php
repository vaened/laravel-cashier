<?php
/**
 * Created by enea dhack - 30/05/2017 06:03 PM.
 */

namespace Enea\Cashier\Contracts;

/**
 * When implementing this interface, it is possible to assign a discount percentage on an item.
 */
interface DiscountableContract
{
    /**
     * Get the item discount in percentage.
     *
     * @return int
     */
    public function getDiscountPercentage();
}
