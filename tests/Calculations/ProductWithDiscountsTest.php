<?php
/**
 * Created by enea dhack - 07/08/2020 17:33.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Modifiers\Discount;

class ProductWithDiscountsTest extends CalculatorTestCase
{
    protected function product(): array
    {
        return [
            'price' => 566.5,
            'quantity' => 3,
            'apply' => [],
            'taxes' => [],
            'discounts' => [
                Discount::percentage(8)->setCode('DISCOUNT-001'),
                Discount::value(100)->setCode('DISCOUNT-002'),
            ],
        ];
    }

    protected function expectedTotals(): array
    {
        return [
            'unit_price' => 566.5,
            'gross_unit_price' => 566.5,
            'net_unit_price' => 566.5,
            'quantity' => 3,
            'subtotal' => 1699.5,
            'total_taxes' => 0.0,
            'total_discounts' => 235.96,
            'total' => 1463.54
        ];
    }

    protected function expectedDiscounts(): array
    {
        return [
            'DISCOUNT-001' => [
                'code' => 'DISCOUNT-001',
                'description' => 'discount percentage',
                'properties' => [],
                'total' => 135.96,
            ],
            'DISCOUNT-002' => [
                'code' => 'DISCOUNT-002',
                'description' => 'discount value',
                'properties' => [],
                'total' => 100,
            ],
        ];
    }

    protected function expectedTaxes(): array
    {
        return [];
    }
}
