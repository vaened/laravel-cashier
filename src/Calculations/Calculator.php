<?php
/**
 * Created by enea dhack - 31/07/2020 17:47.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Contracts\{CalculableContract, TotalizableContract};
use Enea\Cashier\Modifiers\DiscountContract;
use Enea\Cashier\Modifiers\TaxContract;

class Calculator implements CalculatorContract
{
    protected PriceEvaluator $evaluator;

    private float $unitPrice;

    private int $quantity;

    private array $taxes = [];

    private array $uses = [];

    private array $discounts = [];

    public function __construct(CalculableContract $calculable, int $quantity, array $taxes, array $discounts)
    {
        $this->unitPrice = $calculable->getUnitPrice();
        $this->evaluator = new PriceEvaluator($calculable->getUnitPrice(), $taxes, []);
        $this->setDiscounts($discounts);
        $this->setQuantity($quantity);
        $this->setTaxes($taxes);
    }

    public function applyTaxes(array $taxNames): void
    {
        $taxNames = array_map(fn(string $taxName): string => $taxName, $taxNames);
        $this->uses = $taxNames;
        $this->evaluator = new PriceEvaluator($this->unitPrice, $this->taxes, $taxNames);
    }

    public function setDiscounts(array $discounts): void
    {
        foreach ($discounts as $discount) {
            $this->addDiscount($discount);
        }
    }

    public function addDiscount(DiscountContract $discount): void
    {
        $this->discounts[$discount->getDiscountCode()] = $discount;
    }

    public function getDiscount(string $code): ?Discounted
    {
        $discount = $this->discounts[$code] ?? null;
        return $discount instanceof DiscountContract ? $this->toDiscounted($discount) : null;
    }

    public function removeDiscount(string $code): void
    {
        unset($this->discounts[$code]);
    }

    public function getTax(string $name): ?Taxed
    {
        $tax = $this->getUsesTaxes()[$name] ?? null;
        return $tax instanceof TaxContract ? $this->toTaxed($tax) : null;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->evaluator->getUnitPrice();
    }

    public function getGrossUnitPrice(): float
    {
        return $this->evaluator->getGrossUnitPrice();
    }

    public function getNetUnitPrice(): float
    {
        return $this->evaluator->getNetUnitPrice();
    }

    public function getSubtotal(): float
    {
        return $this->getUnitPrice() * $this->getQuantity();
    }

    public function getTotalDiscounts(): float
    {
        return $this->sumTotalFrom($this->getDiscounts());
    }

    public function getTotalTaxes(): float
    {
        return $this->sumTotalFrom($this->getTaxes());
    }

    public function getTotal(): float
    {
        return $this->getSubtotal() - $this->getTotalDiscounts() + $this->getTotalTaxes();
    }

    public function getTaxes(): array
    {
        return array_map(fn(TaxContract $tax) => $this->toTaxed($tax), $this->getUsesTaxes());
    }

    public function getDiscounts(): array
    {
        return array_map(fn(DiscountContract $discount) => $this->toDiscounted($discount), $this->discounts);
    }

    protected function setTaxes(array $taxes): void
    {
        foreach ($taxes as $tax) {
            $this->addTax($tax);
        }
    }

    protected function addTax(TaxContract $tax): void
    {
        $this->taxes[$tax->getName()] = $tax;
    }

    protected function getUsesTaxes(): array
    {
        return array_intersect_key($this->taxes, array_flip($this->uses));
    }

    protected function toDiscounted(DiscountContract $discount): Discounted
    {
        return new Discounted($discount, $this->getGrossSubTotal());
    }

    protected function toTaxed(TaxContract $tax): Taxed
    {
        return new Taxed($tax, $this->getGrossSubTotal());
    }

    protected function getGrossSubTotal(): float
    {
        return $this->getGrossUnitPrice() * $this->getQuantity();
    }

    protected function sumTotalFrom(array $totalizables): float
    {
        return array_reduce($totalizables, fn(
            float $acc,
            TotalizableContract $totalizable
        ): float => $acc + $totalizable->getTotal(), 0.0);
    }
}
