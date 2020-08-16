<?php
/**
 * Created by enea dhack - 30/05/2017 03:36 PM.
 */

namespace Enea\Cashier\Items;

use Enea\Cashier\{HasProperties, IsJsonable};
use Enea\Cashier\Calculations\{Calculator, CashierContract, Taxed};
use Enea\Cashier\Calculations\Discounted;
use Enea\Cashier\Contracts\{AttributableContract, CalculableContract, KeyableContract, ProductContract};
use Illuminate\Contracts\Support\{Arrayable, Jsonable};

abstract class CartItem implements Arrayable, Jsonable, AttributableContract, KeyableContract
{
    use IsJsonable, HasProperties;

    private CashierContract $cashier;

    public function __construct(ProductContract $product, int $quantity, array $taxes, array $discounts)
    {
        $this->cashier = $this->createCashier($product, $quantity, $taxes, $discounts);
        $this->setProperties($product->getProperties());
    }

    abstract public function getProduct(): ProductContract;

    public function getUniqueIdentificationKey(): string
    {
        return $this->getProduct()->getUniqueIdentificationKey();
    }

    public function getShortDescription(): string
    {
        return $this->getProduct()->getShortDescription();
    }

    public function getQuantity(): int
    {
        return $this->getCashier()->getQuantity();
    }

    public function getDiscount(string $code): ?Discounted
    {
        return $this->getCashier()->getDiscount($code);
    }

    public function getTax(string $name): ?Taxed
    {
        return $this->getCashier()->getTax($name);
    }

    public function getCalculator(): Calculator
    {
        return new Calculator($this->getCashier());
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge([
            'id' => $this->getUniqueIdentificationKey(),
            'short_description' => $this->getShortDescription(),
            'properties' => $this->getProperties(),
        ], $this->getCalculator()->toArray());
    }

    protected final function getCashier(): CashierContract
    {
        return $this->cashier;
    }

    protected function createCashier(CalculableContract $calculable, int $quantity, array $taxes, array $discounts): CashierContract
    {
        return app(CashierContract::class, compact('calculable', 'quantity', 'taxes', 'discounts'));
    }
}
