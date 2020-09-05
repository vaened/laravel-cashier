<?php
/**
 * Created by enea dhack - 05/10/2017.
 */

namespace Enea\Cashier\Contracts;

interface CalculableContract
{
    public function getUnitPrice(): float;
}
