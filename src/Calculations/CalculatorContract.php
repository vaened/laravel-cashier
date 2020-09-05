<?php
/**
 * Created by enea dhack - 16/06/17 09:05 PM.
 */

namespace Enea\Cashier\Calculations;

interface CalculatorContract extends RatableContract
{
    public function applyTaxes(array $taxNames): void;

    public function setDiscounts(array $discounts): void;

    public function removeDiscount(string $code): void;

    public function setQuantity(int $quantity): void;
}
