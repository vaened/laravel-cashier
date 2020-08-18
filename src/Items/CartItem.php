<?php
/**
 * Created by enea dhack - 30/05/2017 03:36 PM.
 */

namespace Enea\Cashier\Items;

use Enea\Cashier\{HasProperties, IsJsonable};
use Enea\Cashier\Calculations\{CalculatorContract, Cashier, Discounted, Taxed};
use Enea\Cashier\Contracts\{AttributableContract, CalculableContract, KeyableContract, ProductContract};
use Illuminate\Contracts\Support\{Arrayable, Jsonable};

abstract class CartItem implements Arrayable, Jsonable, AttributableContract, KeyableContract
{
    use IsJsonable, HasProperties;

    private CalculatorContract $calculator;

    public function __construct(ProductContract $product, int $quantity, array $taxes, array $discounts)
    {
        $this->calculator = $this->createCalculator($product, $quantity, $taxes, $discounts);
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

    public function getDiscounts(): array
    {
        return $this->getCashier()->getDiscounts();
    }

    public function getTax(string $name): ?Taxed
    {
        return $this->getCashier()->getTax($name);
    }

    public function getTaxes(): array
    {
        return $this->getCashier()->getTaxes();
    }

    public function getCashier(): Cashier
    {
        return new Cashier($this->getCalculator());
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
        ], $this->getCashier()->toArray());
    }

    protected final function getCalculator(): CalculatorContract
    {
        return $this->calculator;
    }

    protected function createCalculator(CalculableContract $calculable, int $quantity, array $taxes, array $discounts): CalculatorContract
    {
        return app(CalculatorContract::class, compact('calculable', 'quantity', 'taxes', 'discounts'));
    }
}
