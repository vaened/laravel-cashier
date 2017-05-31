<?php
/**
 * Created by enea dhack - 30/05/2017 04:42 PM
 */

namespace Enea\Tests;


use Enea\Cashier\Calculator;

class CalculatorTest extends TestCase
{
    const  PRICE  = 131.4;

    const  QUANTITY = 2;

    const  IGV = 18;

    const  DISCOUNT = 5;

    const PLAN = 10;

    protected function getCalculator()
    {
        return new Calculator(self::PRICE, self::QUANTITY, self::IGV, self::DISCOUNT, self::PLAN);
    }

    /**
     * @test
     */
    function calculation_of_subtotal_is_correct()
    {
        $this->assertSame($this->getCalculator()->getSubtotal(), 262.8);
    }

    /**
     * @test
     */
    function calculation_of_tax_is_correct()
    {
        $this->assertSame($this->getCalculator()->getImpost(), 47.304);
    }

    /**
     * @test
     */
    function calculation_of_discount_is_correct()
    {
        $this->assertSame($this->getCalculator()->getDiscount(), 13.14);
    }

    /**
     * @test
     */
    function calculation_of_plan_discount_is_correct()
    {
        $this->assertSame($this->getCalculator()->getPlanDiscount(), 26.28);
    }

    /**
     * @test
     */
    function calculation_of_total_discounts_is_correct()
    {
        $this->assertSame($this->getCalculator()->getTotalDiscounts(), 39.42);
    }

    /**
     * @test
     */
    function calculation_of_tax_percentage_is_correct()
    {
        $this->assertSame($this->getCalculator()->getImpostPercentage(), 0.18);
    }

    /**
     * @test
     */
    function calculation_of_total_is_correct()
    {
        $this->assertSame($this->getCalculator()->getDefinitiveTotal(), 270.684);
    }

}