<?php
/**
 * Created by enea dhack - 17/06/17 01:14 PM.
 */

namespace Enea\Tests;

use Enea\Cashier\Calculator;

class CalculatorTest extends TestCase
{
    protected function getCalculator($impost = 18, $discount = 23, $plan = 10)
    {
        $properties = [
           635.90, // base price
           3, // quantity
           $impost, // tax percentage
           $discount, // discount percentage
           $plan, //plan discount percentage
        ];

        return new Calculator(...$properties);
    }

    public function test_calculations_are_accurate_with_imposts_and_discounts()
    {
        $calculator = $this->getCalculator($impost = 18, $discount = 23, $plan = 10);

        $this->assertSame($calculator->getBasePrice(), 635.90);

        $this->assertSame($calculator->getSubtotal(), 1907.70);

        $this->assertSame($calculator->getDiscount(), 438.771);

        $this->assertSame($calculator->getPlanDiscount(), 190.77);

        $this->assertSame($calculator->getTotalDiscounts(), 629.541);

        $this->assertSame($calculator->getImpost(), 343.386);

        $this->assertSame($calculator->getDefinitiveTotal(), 1621.545);
    }

    public function test_calculations_are_accurate_only_impost()
    {
        $calculator = $this->getCalculator($impost = 18, $discount = 0, $plan = 0);

        $this->assertSame($calculator->getBasePrice(), 635.90);

        $this->assertSame($calculator->getSubtotal(), 1907.70);

        $this->assertSame($calculator->getDiscount(), 0.0);

        $this->assertSame($calculator->getPlanDiscount(), 0.0);

        $this->assertSame($calculator->getTotalDiscounts(), 0.0);

        $this->assertSame($calculator->getImpost(), 343.386);

        $this->assertSame($calculator->getDefinitiveTotal(), 2251.086);
    }

    public function test_calculations_are_accurate_only_discount()
    {
        $calculator = $this->getCalculator($impost = 0, $discount = 12, $plan = 0);

        $this->assertSame($calculator->getBasePrice(), 635.90);

        $this->assertSame($calculator->getSubtotal(), 1907.70);

        $this->assertSame($calculator->getDiscount(), 228.924);

        $this->assertSame($calculator->getPlanDiscount(), 0.0);

        $this->assertSame($calculator->getTotalDiscounts(), 228.924);

        $this->assertSame($calculator->getImpost(), 0.0);

        $this->assertSame($calculator->getDefinitiveTotal(), 1678.776);
    }

    public function test_calculations_are_accurate_only_plan_discount()
    {
        $calculator = $this->getCalculator($impost = 0, $discount = 0, $plan = 16);

        $this->assertSame($calculator->getBasePrice(), 635.90);

        $this->assertSame($calculator->getSubtotal(), 1907.70);

        $this->assertSame($calculator->getDiscount(), 0.0);

        $this->assertSame($calculator->getPlanDiscount(), 305.232);

        $this->assertSame($calculator->getTotalDiscounts(), 305.232);

        $this->assertSame($calculator->getImpost(), 0.0);

        $this->assertSame($calculator->getDefinitiveTotal(), 1602.468);
    }
}
