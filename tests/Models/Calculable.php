<?php
/**
 * Created by enea dhack - 07/08/2020 12:50.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\CalculableContract;

class Calculable implements CalculableContract
{
    private float $unitPrice;

    public function __construct(float $unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }
}
