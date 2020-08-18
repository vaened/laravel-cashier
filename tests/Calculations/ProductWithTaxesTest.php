<?php
/**
 * Created by enea dhack - 07/08/2020 17:45.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Modifiers\Tax;

class ProductWithTaxesTest extends CalculatorTestCase
{
    protected function product(): array
    {
        return [
            'price' => 118,
            'quantity' => 2,
            'apply' => ['EXAMPLE-TAX-01', 'EXAMPLE-TAX-02'],
            'discounts' => [],
            'taxes' => [
                Tax::included('EXAMPLE-TAX-01', 18.0),
                Tax::excluded('EXAMPLE-TAX-02', 10.0),
            ],
        ];
    }

    protected function expectedTotals(): array
    {
        return [
            'unit_price' => 100.0,
            'gross_unit_price' => 100.0,
            'net_unit_price' => 128.0,
            'quantity' => 2,
            'subtotal' => 200.0,
            'total_taxes' => 56.0,
            'total_discounts' => 0.0,
            'total' => 256.0
        ];
    }

    protected function expectedDiscounts(): array
    {
        return [];
    }

    protected function expectedTaxes(): array
    {
        return [
            'EXAMPLE-TAX-01' => [
                'name' => 'EXAMPLE-TAX-01',
                'percentage' => 18.0,
                'is_included' => true,
                'total' => 36.0,
            ],
            'EXAMPLE-TAX-02' => [
                'name' => 'EXAMPLE-TAX-02',
                'percentage' => 10.0,
                'is_included' => false,
                'total' => 20.0,
            ],
        ];
    }
}
