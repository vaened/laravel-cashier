<?php
/**
 * Created by enea dhack - 17/06/17 01:14 PM.
 */

namespace Enea\Tests;

use Enea\Cashier\Calculations\Calculator;
use Enea\Cashier\Modifiers\Discounts\Discount;
use Enea\Cashier\Modifiers\Taxes\IGV;

class CalculatorTest extends TestCase
{
    protected function getCalculator(array $params)
    {
        $params = array_values($params);
        return new Calculator(...$params);
    }

    public function test_calculations_are_accurate_with_imposts_and_discounts()
    {
        $params = [
            'price' => 123.456,
            'quantity' => 4,
            'taxes' => collect([
                new IGV()
            ]),
            'discounts' => collect([
                new Discount('PTS', 'parties', 10),
                new Discount('ANN', 'anniversary', 5),
            ])
        ];

        $calculator = $this->getCalculator($params);

        $this->assertSame($calculator->getBasePrice(), 123.456);
        $this->assertSame($calculator->getSubtotal(), 493.824);
        $this->assertSame($calculator->getDiscount('PTS')->getTotalExtracted(), 49.382);
        $this->assertSame($calculator->getDiscount('ANN')->getTotalExtracted(), 24.691);
        $this->assertSame($calculator->getTotalDiscounts(), 74.074);
        $this->assertSame($calculator->getQuantity(), 4);
        $this->assertSame($calculator->getTotalTaxes(), 88.888);
        $this->assertSame($calculator->getDefinitiveTotal(), 508.639);
    }

    public function test_tax_calculations_included_are_correct()
    {
        $params = [
            'price' => 173.412,
            'quantity' => 4,
            'taxes' => collect([
                new IGV(18, true)
            ]),
            'discounts' => collect([
                new Discount('PTS', 'parties', 12),
                new Discount('TEST', 'test discount', 8),
            ])
        ];

        $calculator = $this->getCalculator($params);

        $this->assertSame($calculator->getBasePrice(), 146.959);
        $this->assertSame($calculator->getSubtotal(), 587.837);

        $this->assertSame($calculator->getDiscount('PTS')->getTotalExtracted(), 70.540);
        $this->assertSame($calculator->getDiscount('TEST')->getTotalExtracted(), 47.027);
        $this->assertSame($calculator->getTotalDiscounts(), 117.567);

        $this->assertSame($calculator->getQuantity(), 4);

        $this->assertSame($calculator->getTotalTaxes(), 105.811);
        $this->assertSame($calculator->getDefinitiveTotal(), 576.081);
    }

    public function test_calculations_are_accurate_only_impost()
    {
        $params = [
            'price' => 987.654,
            'quantity' => 3,
            'taxes' => collect([
                new IGV(21)
            ]),
            'discounts' => null
        ];
        $calculator = $this->getCalculator($params);

        $this->assertSame($calculator->getBasePrice(), 987.654);
        $this->assertSame($calculator->getSubtotal(), 2962.962);
        $this->assertSame($calculator->getTotalDiscounts(), 0.0);
        $this->assertSame($calculator->getQuantity(), 3);
        $this->assertSame($calculator->getTotalTaxes(), 622.222);
        $this->assertSame($calculator->getDefinitiveTotal(), 3585.184);
    }

    public function test_calculations_are_accurate_only_discount()
    {
        $params = [
            'price' => 108.960,
            'quantity' => 3,
            'taxes' => null,
            'discounts' => collect([
                new Discount('ANN', 'anniversary', 13, collect(['max' => 18])),
                new Discount('TEST', 'test discount', 1),
            ])
        ];
        $calculator = $this->getCalculator($params);

        $this->assertSame($calculator->getBasePrice(), 108.960);
        $this->assertSame($calculator->getSubtotal(), 326.88);

        $this->assertSame($calculator->getDiscount('ANN')->getTotalExtracted(), 42.494);
        $this->assertSame($calculator->getDiscount('ANN')->getPercentage(), 13);
        $this->assertSame($calculator->getDiscount('TEST')->getTotalExtracted(), 3.269);
        $this->assertSame($calculator->getDiscount('TEST')->getPercentage(), 1);

        $calculator->addDiscount(new Discount('NEW', 'only news', 8));

        $this->assertSame($calculator->getDiscount('NEW')->getTotalExtracted(), 26.150);
        $this->assertSame($calculator->getDiscount('NEW')->getPercentage(), 8);

        $this->assertSame($calculator->getTotalDiscounts(), 71.914);

        $this->assertSame($calculator->getTotalTaxes(), 0.0);
        $this->assertSame($calculator->getDefinitiveTotal(), 254.966);

        $calculator->removeDiscount('TEST');

        $this->assertNull($calculator->getDiscount('TEST'));
        $this->assertSame($calculator->getTotalDiscounts(), 68.645);
        $this->assertSame($calculator->getTotalTaxes(), 0.0);
        $this->assertSame($calculator->getDefinitiveTotal(), 258.235);

    }
}
