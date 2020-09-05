<?php
/**
 * Created by enea dhack - 09/08/2020 23:39.
 */

namespace Enea\Tests\Items;

use Enea\Cashier\Items\ProductCartItem;
use Enea\Cashier\Modifiers\Discount;
use Enea\Cashier\Taxes;
use Enea\Tests\Models\Product;
use Enea\Tests\TestCase;

class ProductTest extends TestCase
{
    private ProductCartItem $cartItem;

    public function setUp(): void
    {
        parent::setUp();
        $this->cartItem = new ProductCartItem($this->quoted(), 3);
        $this->cartItem->applyTaxes([Taxes::IGV]);
        $this->cartItem->addDiscount(Discount::percentage(15.5)->setCode('GENERIC'));
    }

    public function test_product_is_loaded(): void
    {
        $this->assertEquals(1, $this->cartItem->getUniqueIdentificationKey());
        $this->assertEquals(1, $this->cartItem->getProduct()->getUniqueIdentificationKey());
    }

    public function test_quantity_is_loaded(): void
    {
        $this->assertEquals(3, $this->cartItem->getQuantity());
    }

    public function test_overwrite_the_quantity(): void
    {
        $this->cartItem->setQuantity(5);
        $this->assertEquals(5, $this->cartItem->getQuantity());
    }

    public function test_taxes_is_loaded(): void
    {
        $IVG = $this->cartItem->getTax(Taxes::IGV);

        $this->assertNotNull($IVG);
        $this->assertEquals(286.773, $IVG->getTotal());
        $this->assertCount(1, $this->cartItem->getTaxes());
    }

    public function test_overwrite_the_taxes_to_use(): void
    {
        $this->cartItem->applyTaxes([]);
        $IVG = $this->cartItem->getTax(Taxes::IGV);

        $this->assertNull($IVG);
        $this->assertCount(0, $this->cartItem->getTaxes());
    }

    public function test_discounts_is_loaded(): void
    {
        $discount = $this->cartItem->getDiscount('GENERIC');

        $this->assertNotNull($discount);
        $this->assertEquals(261.469, $discount->getTotal());
        $this->assertCount(1, $this->cartItem->getDiscounts());
    }

    public function test_remove_a_discount(): void
    {
        $this->cartItem->removeDiscount('GENERIC');
        $discount = $this->cartItem->getDiscount('GENERIC');

        $this->assertNull($discount);
        $this->assertCount(0, $this->cartItem->getDiscounts());
    }

    public function test_evaluate_calculator(): void
    {
        $cashier = $this->cartItem->getCashier();

        $this->assertEquals(286.773, $cashier->getTotalTaxes());
        $this->assertEquals(261.469, $cashier->getTotalDiscounts());
        $this->assertEquals(1686.897, $cashier->getSubtotal());
        $this->assertEquals(1712.201, $cashier->getTotal());
    }

    public function test_transform_product_to_array(): void
    {
        $this->assertEquals($this->productStructure(), $this->cartItem->toArray());
    }

    public function test_transform_product_to_json(): void
    {
        $this->assertJson(json_encode($this->productStructure()), $this->cartItem->toJson());
    }

    private function productStructure(): array
    {
        return [
            'id' => '1',
            'short_description' => 'example product',
            'properties' => [
                'full_description' => 'full product description'
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
                    'name' => 'IGV',
                    'percentage' => 17.0,
                    'is_included' => true,
                    'total' => 286.773,
                ],
            ],
            'total' => 1712.201,
        ];
    }

    private function quoted(): Product
    {
        return new Product([
            'id' => 1,
            'short_description' => 'example product',
            'full_description' => 'full product description',
            'sale_price' => 657.89,
            'igv_pct' => 17,
        ]);
    }
}
