<?php
/**
 * Created by enea dhack - 01/08/2020 0:37.
 */

namespace Enea\Cashier\Contracts;

interface TotalizableContract
{
    public function getTotal(): float;
}