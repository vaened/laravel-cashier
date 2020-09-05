<?php
/**
 * Created by enea dhack - 30/05/2017 06:03 PM.
 */

namespace Enea\Cashier\Contracts;

interface DiscountableContract
{
    public function getDiscounts(): array;
}
