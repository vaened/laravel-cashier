<?php
/**
 * Created by enea dhack - 07/08/2020 11:55.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Calculations\Cashier;
use Enea\Cashier\Calculations\Calculator;
use Enea\Tests\Models\Calculable;
use Enea\Tests\TestCase;

abstract class CalculatorTestCase extends TestCase
{
    private Calculator $calculator;

    abstract protected function product(): array;

    abstract protected function expectedTotals(): array;

    abstract protected function expectedDiscounts(): array;

    abstract protected function expectedTaxes(): array;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializeCashier();
    }

    public function test_unit_price(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['unit_price'], $this->calculator->getUnitPrice());
    }

    public function test_gross_unit_price(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['gross_unit_price'], $this->calculator->getGrossUnitPrice());
    }

    public function test_net_unit_price(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['net_unit_price'], $this->calculator->getNetUnitPrice());
    }

    public function test_quantity(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['quantity'], $this->calculator->getQuantity());
    }

    public function test_subtotal(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['subtotal'], $this->calculator->getSubtotal());
    }

    public function test_total_taxes(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['total_taxes'], $this->calculator->getTotalTaxes());
    }

    public function test_total_discounts(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['total_discounts'], $this->calculator->getTotalDiscounts());
    }

    public function test_total(): void
    {
        $totals = $this->expectedTotals();
        $this->assertEquals($totals['total'], $this->calculator->getTotal());
    }

    public function test_taxes(): void
    {
        $taxes = $this->expectedTaxes();

        $this->assertCount(count($taxes), $this->calculator->getTaxes());
        foreach ($this->expectedTaxes() as $tax) {
            $taxed = $this->calculator->getTax($tax['name']);
            $this->assertEquals($tax, $taxed->toArray());
        }
    }

    public function test_discounts(): void
    {
        $discounts = $this->expectedDiscounts();

        $this->assertCount(count($discounts), $this->calculator->getDiscounts());
        foreach ($discounts as $discount) {
            $discounted = $this->calculator->getDiscount($discount['code']);
            $this->assertEquals($discount, $discounted->toArray());
        }
    }

    public function test_calculator(): void
    {
        $cashier = new Cashier($this->calculator);
        $totals = $this->expectedTotals();

        $this->assertEquals([
            'unit_price' => $totals['unit_price'],
            'gross_unit_price' => $totals['gross_unit_price'],
            'net_unit_price' => $totals['net_unit_price'],
            'quantity' => $totals['quantity'],
            'subtotal' => $totals['subtotal'],
            'total_discounts' => $totals['total_discounts'],
            'discounts' => $this->expectedDiscounts(),
            'total_taxes' => $totals['total_taxes'],
            'taxes' => $this->expectedTaxes(),
            'total' => $totals['total'],
        ], $cashier->toArray());
    }

    private function initializeCashier(): void
    {
        $product = $this->product();
        $params = [new Calculable($product['price']), $product['quantity'], $product['taxes'], []];
        $cashier = new Calculator(...$params);
        $cashier->applyTaxes($product['apply']);
        $cashier->setDiscounts($product['discounts']);
        $this->calculator = $cashier;
    }
}