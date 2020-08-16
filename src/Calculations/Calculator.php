<?php
/**
 * Created by enea dhack - 30/05/2017 02:54 PM.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Helpers;
use Enea\Cashier\IsJsonable;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;

class Calculator implements RatableContract, Jsonable, Arrayable, JsonSerializable
{
    use IsJsonable;

    private CashierContract $calculator;

    public function __construct(CashierContract $calculator)
    {
        $this->calculator = $calculator;
    }

    public function getTaxes(): array
    {
        return $this->calculator->getTaxes();
    }

    public function getDiscounts(): array
    {
        return $this->calculator->getDiscounts();
    }

    public function getQuantity(): int
    {
        return $this->calculator->getQuantity();
    }

    public function getUnitPrice(): float
    {
        return Helpers::decimal($this->calculator->getUnitPrice());
    }

    public function getGrossUnitPrice(): float
    {
        return Helpers::decimal($this->calculator->getGrossUnitPrice());
    }

    public function getNetUnitPrice(): float
    {
        return Helpers::decimal($this->calculator->getNetUnitPrice());
    }

    public function getSubtotal(): float
    {
        return Helpers::decimal($this->calculator->getSubtotal());
    }

    public function getTotalDiscounts(): float
    {
        return $this->calculator->getTotalDiscounts();
    }

    public function getTotalTaxes(): float
    {
        return $this->calculator->getTotalTaxes();
    }

    public function getTotal(): float
    {
        return Helpers::decimal($this->calculator->getTotal());
    }

    public function toArray()
    {
        return [
            'unit_price' => $this->getUnitPrice(),
            'gross_unit_price' => $this->getGrossUnitPrice(),
            'net_unit_price' => $this->getNetUnitPrice(),
            'quantity' => $this->getQuantity(),
            'subtotal' => $this->getSubtotal(),
            'total_discounts' => $this->getTotalDiscounts(),
            'discounts' => Helpers::convertToArray($this->getDiscounts()),
            'total_taxes' => $this->getTotalTaxes(),
            'taxes' => Helpers::convertToArray($this->getTaxes()),
            'total' => $this->getTotal(),
        ];
    }
}
