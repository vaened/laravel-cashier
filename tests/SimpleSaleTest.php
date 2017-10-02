<?php
/**
 * Created by enea dhack - 17/06/17 01:56 PM.
 */

namespace Enea\Tests;

use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Documents\Invoice;
use Enea\Cashier\Documents\Voucher;
use Enea\Cashier\Modifiers\Discounts\Discount;
use Enea\Cashier\SalableItem;
use Enea\Cashier\ShoppingCart;
use Enea\Tests\Models\Client;

class SimpleSaleTest extends TestCase
{
    public function test_a_purchase_starts()
    {
        $client = new Client();
        $document = new Invoice();
        $manager = $this->getManager();

        $shopping = $manager->initialize($client, $document);

        $_token = $shopping->getGeneratedToken();

        $this->assertNotEmpty($_token);

        $shopping = $manager->find($_token);

        $this->assertTrue($shopping instanceof ShoppingCart);
        $this->assertEquals($shopping->getGeneratedToken(), $_token);
    }

    public function test_manage_the_products_in_a_shopping_cart()
    {
        $shopping = $this->getShoppingCart();

        $quantity = 10;
        $key = 1;

        $keyboard = $this->salable(['id' => 'k-1015', 'description' => 'keyboard', 'custom_property' => 'custom']);
        $this->assertTrue($shopping->push($keyboard, $quantity));

        $item = $shopping->find($keyboard->getItemKey());

        $this->assertTrue($item instanceof SalableItem);

        $this->assertSame($item->getSalable()->getShortDescription(), 'keyboard');
        $this->assertSame($item->getElementKey(), 'k-1015');
        $this->assertSame($item->getAdditionalAttribute('custom_property'), 'custom');
        $this->assertNull($item->getAdditionalAttribute('non-existent'));

        $this->assertTrue($item->getSalable() instanceof $keyboard);
        $this->assertTrue($item->getQuantity() === $quantity);
        $this->assertTrue($shopping->count() === 1);

        $this->assertFalse($shopping->remove('non-existent'));
        $this->assertTrue($shopping->count() === 1);

        $this->assertTrue($shopping->remove($item->getElementKey()));
        $this->assertTrue($shopping->count() === 0);

        $this->assertTrue($shopping->push($this->salable(['id' => $key]), $quantity));
        $this->assertTrue($shopping->count() === 1);

        $this->assertFalse($shopping->push($this->salable(['id' => $key])));
        $this->assertTrue($shopping->count() === 1);

        $this->assertTrue($shopping->push($keyboard, $quantity));
        $item = $shopping->find($keyboard->getItemKey());
        $item->setQuantity(10);

        $item = null;

        $item = $shopping->find($keyboard->getItemKey());
        $this->assertTrue($item->getQuantity() === 10);
    }

    public function test_correct_calculations_are_performed()
    {
        $shopping = $this->getShoppingCart();
        $product = $this->salable(['price' => 36.99]);

        $shopping->setDocument(new Invoice());
        $this->assertInstanceOf(Invoice::class, $shopping->getDocument());
        $this->assertInstanceOf(DocumentContract::class, $shopping->getDocument());
        $this->assertSame($shopping->getDocument()->getKeyDocument(), 'invoice');

        $this->assertTrue($shopping->push($product, 3));
        $this->assertSame($shopping->getSubtotal(), 110.97);
        $this->assertSame($shopping->getTotalTaxes(), 19.975);
        $this->assertSame($shopping->getDefinitiveTotal(), 130.945);

        $shopping->setDocument(new Voucher());
        $this->assertInstanceOf(Voucher::class, $shopping->getDocument());
        $this->assertInstanceOf(DocumentContract::class, $shopping->getDocument());
        $this->assertSame($shopping->getDocument()->getKeyDocument(), 'voucher');

        $this->assertSame($shopping->getSubtotal(), 110.97);
        $this->assertSame($shopping->getTotalTaxes(), 0.0);
        $this->assertSame($shopping->getDefinitiveTotal(), 110.97);

        $this->assertTrue($shopping->remove($product->getItemKey()));

        $product = $this->discountableProduct(['id' => 'PD001', 'price' => 128.9, 'discount' => 10]);

        $shopping->setDocument(new Invoice());
        $this->assertTrue($shopping->push($product, 2));
        $this->assertSame($shopping->getSubtotal(), 257.8);
        $this->assertSame($shopping->getTotalDiscounts(), 25.78);
        $this->assertSame($shopping->getTotalTaxes(), 46.404);
        $this->assertSame($shopping->getDefinitiveTotal(), 278.424);

        $shopping->setDocument(new Voucher());
        $this->assertSame($shopping->getSubtotal(), 257.8);
        $this->assertSame($shopping->getTotalDiscounts(), 25.78);
        $this->assertSame($shopping->getTotalTaxes(), 0.0);
        $this->assertSame($shopping->getDefinitiveTotal(), 232.02);

        $product = $this->salable(['price' => 36.99]);

        $shopping->setDocument(new Invoice());
        $this->assertTrue($shopping->push($product, 3));
        $this->assertSame($shopping->getSubtotal(), 368.77);
        $this->assertSame($shopping->getTotalDiscounts(), 25.78);
        $this->assertSame($shopping->getTotalTaxes(), 66.379);
        $this->assertSame($shopping->getDefinitiveTotal(), 409.369);
    }
}
