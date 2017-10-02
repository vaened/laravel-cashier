<?php
/**
 * Created by enea dhack - 17/06/17 03:52 PM.
 */

namespace Enea\Cashier\Contracts;

interface CartElementContract extends AttributableContract
{
    /**
     * Key that identifies the article as unique.
     *
     * @return int|string
     */
    public function getItemKey();

    /**
     * Returns item name.
     *
     * @return null|string
     * */
    public function getShortDescription();

    /**
     * Get base price for item.
     *
     * @return float
     */
    public function getBasePrice();
}
