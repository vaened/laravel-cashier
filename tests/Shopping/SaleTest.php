<?php
/**
 * Created by enea dhack - 08/08/2020 16:52.
 */

namespace Enea\Tests\Shopping;

use Enea\Cashier\Documents\Free;
use Enea\Cashier\Documents\Invoice;
use Enea\Cashier\Items\ProductCartItem;
use Enea\Cashier\Modifiers\Discount;
use Enea\Cashier\ShoppingCart;
use Enea\Cashier\Taxes;
use Enea\Tests\DataBaseTestCase;
use Enea\Tests\Models\Client;
use Enea\Tests\Models\Product;

class SaleTest extends DataBaseTestCase
{
    public function test_check_product_existence(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $this->assertTrue($shoppingCart->hasProduct(1));
        $this->assertTrue($shoppingCart->hasProduct(2));
        $this->assertFalse($shoppingCart->hasProduct(4444));
    }

    public function test_find_products(): void
    {
        $shoppingCart = $this->getShoppingCart();

        $keyboard = $shoppingCart->find(1);
        $this->assertEquals('Keyboard K530-rgb', $keyboard->getShortDescription());
        $this->assertNotNull($keyboard->getDiscount('ONLY-TODAY'));

        $backpack = $shoppingCart->find(2);
        $this->assertEquals('Chest Backpack', $backpack->getShortDescription());
        $this->assertEquals(10, $backpack->getQuantity());
    }

    public function test_id_equals_product_id(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $this->assertEquals('1', $shoppingCart->find(1)->getUniqueIdentificationKey());
        $this->assertEquals('2', $shoppingCart->find(2)->getUniqueIdentificationKey());
    }

    public function test_add_product(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $shoppingCart->push(Product::find(3));

        $this->assertNotNull($shoppingCart->find(3));
    }

    public function test_remove_a_product(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $shoppingCart->remove(1);

        $this->assertNull($shoppingCart->find(1));
    }

    public function test_load_global_discount(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $promotional = fn(ProductCartItem $product) => $product->getDiscount('PROMOTIONAL');
        $discounts = $shoppingCart->products()->map($promotional)->filter();

        $this->assertCount(2, $discounts);
    }

    public function test_remove_a_global_discount(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $shoppingCart->removeDiscount('PROMOTIONAL');

        $promotional = fn(ProductCartItem $product) => $product->getDiscount('PROMOTIONAL');
        $discounts = $shoppingCart->products()->map($promotional)->filter();

        $this->assertEmpty($discounts);
    }

    public function test_cart_totals(): void
    {
        $shoppingCart = $this->getShoppingCart();

        $this->assertEquals(1101.695, $shoppingCart->getSubtotal());
        $this->assertEquals(198.305, $shoppingCart->getTotalTaxes());
        $this->assertEquals(233.051, $shoppingCart->getTotalDiscounts());
        $this->assertEquals(1066.949, $shoppingCart->getTotal());
    }

    public function test_clean_a_cart_empties_the_product_list(): void
    {
        $shoppingCart = $this->getShoppingCart();

        $shoppingCart->clear();
        $this->assertEmpty($shoppingCart->products());
    }

    public function test_assign_a_document_updates_all_products(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $shoppingCart->setDocument(Free::create());

        $IGV = fn(ProductCartItem $product) => $product->getTax(Taxes::IGV);
        $taxes = $shoppingCart->products()->map($IGV)->filter();

        $this->assertEmpty($taxes);
    }

    public function test_transform_cart_to_array(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $cart = $shoppingCart->toArray();

        $this->assertCount(2, $cart['products']);

        $this->assertEquals([
            'token' => $shoppingCart->getGeneratedToken(),
            'subtotal' => 1101.695,
            'total' => 1066.949,
            'total_taxes' => 198.305,
            'total_discounts' => 233.051,
            'quote' => null,
            'properties' => [
                'greeting' => 'konichiwa'
            ],
            'buyer' => [
                "id" => "1",
                "properties" => [
                    "full_name" => "Shuuzou Oshimi",
                ],
            ],
            'discounts' => [
                "PROMOTIONAL" => [
                    "code" => "PROMOTIONAL",
                    "description" => "discount percentage",
                    "properties" => [],
                ],
            ],
            'document' => [
                "key" => "invoice",
                "taxes" => ["IGV"],
            ],
        ], [
            'token' => $cart['token'],
            'properties' => $cart['properties'],
            'buyer' => $cart['buyer'],
            'subtotal' => $cart['subtotal'],
            'total' => $cart['total'],
            'total_taxes' => $cart['total_taxes'],
            'total_discounts' => $cart['total_discounts'],
            'discounts' => $cart['discounts'],
            'document' => $cart['document'],
            'quote' => $cart['quote'],
        ]);
    }

    public function getShoppingCart(): ShoppingCart
    {
        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create([Taxes::IGV]));
        $shoppingCart->addDiscount(Discount::percentage(15)->setCode('PROMOTIONAL'));
        $shoppingCart->putProperty('greeting', 'konichiwa');

        $keyboard = $shoppingCart->push(Product::find(1), 5);
        $keyboard->addDiscount(Discount::percentage(10)->setCode('ONLY-TODAY'));

        $backpack = $shoppingCart->push(Product::find(2));
        $backpack->setQuantity(10);

        return $shoppingCart;
    }
}
