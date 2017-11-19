<?php
/**
 * Created by enea dhack - 30/05/2017 06:03 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Support\Collection;

/**
 * Implement this interface when it is possible to allocate discounts.
 */
interface DiscountableContract
{
    /**
     * Get the item discount.
     *
     * @return Collection<DiscountContract>
     */
    public function getDiscounts();
}
