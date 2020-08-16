<?php
/**
 * Created by enea dhack - 28/07/2020 20:46.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Contracts\TotalizableContract;

interface RatableContract extends TotalizableContract
{
    public function getQuantity(): int;

    public function getUnitPrice(): float;

    public function getGrossUnitPrice(): float;

    public function getNetUnitPrice(): float;

    public function getTaxes(): array;

    public function getDiscounts(): array;

    public function getSubtotal(): float;

    public function getTotalDiscounts(): float;

    public function getTotalTaxes(): float;
}