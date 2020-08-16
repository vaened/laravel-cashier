<?php
/**
 * Created by enea dhack - 16/06/17 09:05 PM.
 */

namespace Enea\Cashier\Calculations;

interface CashierContract extends RatableContract
{
    public function applyTaxes(array $taxNames): void;

    public function setDiscounts(array $discounts): void;

    public function removeDiscount(string $code): void;

    public function getDiscount(string $code): ?Discounted;

    public function getTax(string $name): ?Taxed;

    public function setQuantity(int $quantity): void;
}
