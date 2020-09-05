<?php
/**
 * Created by enea dhack - 07/08/2020 17:17.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Modifiers\Discount;
use Enea\Cashier\Modifiers\Tax;

class ProductWithDiscountsAndTaxesTest extends CalculatorTestCase
{
    protected function product(): array
    {
        return [
            'price' => 118,
            'quantity' => 10,
            'apply' => ['EXAMPLE-TAX'],
            'taxes' => [
                Tax::included('EXAMPLE-TAX', 18),
            ],
            'discounts' => [
                Discount::percentage(10)->setCode('DISCOUNT-001'),
            ],
        ];
    }

    protected function expectedTotals(): array
    {
        return [
            'unit_price' => 100.0,
            'gross_unit_price' => 100.0,
            'net_unit_price' => 118.0,
            'quantity' => 10,
            'subtotal' => 1000.0,
            'total_taxes' => 180.0,
            'total_discounts' => 100,
            'total' => 1080.0
        ];
    }

    protected function expectedDiscounts(): array
    {
        return [
            'DISCOUNT-001' => [
                'code' => 'DISCOUNT-001',
                'description' => 'discount percentage',
                'properties' => [],
                'total' => 100.0,
            ],
        ];
    }

    protected function expectedTaxes(): array
    {
        return [
            'EXAMPLE-TAX' => [
                'name' => 'EXAMPLE-TAX',
                'percentage' => 18.0,
                'is_included' => true,
                'total' => 180.0,
            ],
        ];
    }
}
