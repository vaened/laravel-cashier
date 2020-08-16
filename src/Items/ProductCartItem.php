<?php
/**
 * Created by enea dhack - 21/07/2020 21:50.
 */

namespace Enea\Cashier\Items;

use Enea\Cashier\Contracts\{ProductContract};
use Enea\Cashier\Modifiers\DiscountContract;

final class ProductCartItem extends CartItem
{
    protected ProductContract $product;

    public function __construct(ProductContract $product, int $quantity, array $additionalTaxes = [], array $discounts = [])
    {
        parent::__construct($product, $quantity, $this->mergeTaxes($product, $additionalTaxes), $discounts);
        $this->product = $product;
    }

    public function getProduct(): ProductContract
    {
        return $this->product;
    }

    public function setQuantity(int $quantity): void
    {
        $this->getCashier()->setQuantity($quantity);
    }

    public function addDiscounts(array $discounts): void
    {
        $this->getCashier()->setDiscounts($discounts);
    }

    public function addDiscount(DiscountContract $discount): void
    {
        $this->addDiscounts([$discount]);
    }

    public function removeDiscount(string $code): void
    {
        $this->getCashier()->removeDiscount($code);
    }

    public function applyTaxes(array $taxNames): void
    {
        $this->getCashier()->applyTaxes($taxNames);
    }

    private function mergeTaxes(ProductContract $product, array $additionalTaxes): array
    {
        return array_merge($product->getTaxes(), $additionalTaxes);
    }
}
