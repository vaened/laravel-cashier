<?php
/**
 * Created by enea dhack - 08/08/2020 19:28.
 */

namespace Enea\Tests\Items;

use Enea\Cashier\Items\QuotedProductCartItem;
use Enea\Cashier\Taxes;
use Enea\Tests\DataBaseTestCase;
use Enea\Tests\Models\QuotedProduct;

class QuotedProductTest extends DataBaseTestCase
{
    private QuotedProductCartItem $cartItem;

    public function setUp(): void
    {
        parent::setUp();
        $this->cartItem = new QuotedProductCartItem($this->quoted());
    }

    public function test_product_is_loaded(): void
    {
        $this->assertEquals(1, $this->cartItem->getUniqueIdentificationKey());
        $this->assertEquals(1, $this->cartItem->toSell()->getUniqueIdentificationKey());
        $this->assertEquals(1, $this->cartItem->getProduct()->getUniqueIdentificationKey());
    }

    public function test_taxes_is_loaded(): void
    {
        $IVG = $this->cartItem->getTax(Taxes::IGV);

        $this->assertNotNull($IVG);
        $this->assertEquals(286.773, $IVG->getTotal());
        $this->assertCount(1, $this->cartItem->getTaxes());
    }

    public function test_discounts_is_loaded(): void
    {
        $discount = $this->cartItem->getDiscount('GENERIC');

        $this->assertNotNull($discount);
        $this->assertEquals(261.469, $discount->getTotal());
        $this->assertCount(1, $this->cartItem->getDiscounts());
    }

    public function test_evaluate_calculator(): void
    {
        $cashier = $this->cartItem->getCashier();

        $this->assertEquals(286.773, $cashier->getTotalTaxes());
        $this->assertEquals(261.469, $cashier->getTotalDiscounts());
        $this->assertEquals(1686.897, $cashier->getSubtotal());
        $this->assertEquals(1712.201, $cashier->getTotal());
    }

    public function test_transform_to_array(): void
    {
        $this->assertEquals([
            'id' => "1",
            'short_description' => 'Keyboard K530-rgb',
            'properties' => [
                'quote_id' => 1,
            ],
            'unit_price' => 562.299,
            'gross_unit_price' => 562.299,
            'net_unit_price' => 657.89,
            'quantity' => 3,
            'subtotal' => 1686.897,
            'total_discounts' => 261.469,
            'discounts' => [
                'GENERIC' => [
                    'code' => 'GENERIC',
                    'description' => 'discount percentage',
                    'properties' => [],
                    'total' => 261.469,
                ],
            ],
            'total_taxes' => 286.773,
            'taxes' => [
                'IGV' => [
                    'name' => "IGV",
                    'percentage' => 17.0,
                    'is_included' => true,
                    'total' => 286.773,
                ],
            ],
            'total' => 1712.201,
        ], $this->cartItem->toArray());
    }

    protected function quoted(): QuotedProduct
    {
        return new QuotedProduct([
            'quote_id' => 1,
            'product_id' => 1,
            'quantity' => 3,
            'sale_price' => 657.89,
            'discount_pct' => 15.5,
            'taxes_pct' => 17,
        ]);
    }
}
