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

    public function test_the_calculations_are_correct_with_discounts_and_dynamic_taxes()
    {
        $shopping = $this->getShoppingCart();
        $anniversary = Discount::make('ANN', 'Anniversary discount', 12);
        $test = Discount::make('TEST', 'Test discount', 5);
        $invoice = Invoice::make()->withTaxIncluded();

        $shopping->setDocument($invoice);
        $shopping->addDiscount($anniversary);

        $product = $this->discountableProduct(['id' => 'PD001', 'price' => 76.926, 'discount' => 1]);
        $this->assertTrue($shopping->push($product));
        $this->assertNotNull($shopping->getDiscount('ANN'));

        $this->assertSame($shopping->getSubtotal(), 65.192);
        $this->assertSame($shopping->getTotalDiscounts(), 8.475);
        $this->assertSame($shopping->getTotalTaxes(), 11.734);
        $this->assertSame($shopping->getDefinitiveTotal(), 68.451);

        $shopping->addDiscount($test);

        $this->assertSame($shopping->getSubtotal(), 65.192);
        $this->assertSame($shopping->getTotalDiscounts(), 11.734);
        $this->assertSame($shopping->getTotalTaxes(), 11.734);
        $this->assertSame($shopping->getDefinitiveTotal(), 65.192);

        $shopping->removeDiscount('ANN');
        $this->assertNull($shopping->getDiscount('ANN'));

        $this->assertSame($shopping->getSubtotal(), 65.192);
        $this->assertSame($shopping->getTotalDiscounts(), 3.911);
        $this->assertSame($shopping->getTotalTaxes(), 11.734);
        $this->assertSame($shopping->getDefinitiveTotal(), 73.015);

        $invoice = Invoice::make()->withoutTaxIncluded();

        $shopping->setDocument($invoice);
        $this->assertNull($shopping->getDiscount('ANN'));
        $this->assertSame($shopping->getSubtotal(), 76.926);
        $this->assertSame($shopping->getTotalDiscounts(), 4.616);
        $this->assertSame($shopping->getTotalTaxes(), 13.847);
        $this->assertSame($shopping->getDefinitiveTotal(), 86.157);
    }
}
