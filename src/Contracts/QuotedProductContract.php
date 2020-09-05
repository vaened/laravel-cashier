<?php
/**
 * Created by enea dhack - 16/06/17 10:40 PM.
 */

namespace Enea\Cashier\Contracts;

interface QuotedProductContract extends ProductContract, DiscountableContract
{
    public function getQuantity(): int;
}
