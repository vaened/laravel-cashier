<?php
/**
 * Created by enea dhack - 30/05/2017 06:03 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Support\Collection;

/**
 * When implementing this interface, it is possible to assign a discount on an item.
 */
interface DiscountableContract
{
    /**
     * Get the item discount.
     *
     * @return Collection<DiscountContract>
     */
    public function getDiscounts();

    /**
     * Returns true if the item is discountable.
     *
     * @return bool
     */
    public function isDiscountable();
}
