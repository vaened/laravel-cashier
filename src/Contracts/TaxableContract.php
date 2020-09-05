<?php
/**
 * Created by enea dhack - 04/10/2017.
 */

namespace Enea\Cashier\Contracts;

interface TaxableContract
{
    public function getTaxes(): array;
}
