<?php
/**
 * Created by enea dhack - 17/06/17 01:56 PM
 */

namespace Enea\Tests;


use Enea\Cashier\SalableItem;
use Enea\Cashier\ShoppingCart;
use Enea\Tests\Documents\Invoice;
use Enea\Tests\Documents\Voucher;
class SimpleSaleTest extends TestCase
{

    function test_a_purchase_starts( )
    {
        $manager = $this->getManager();
        $shopping = $this->getShoppingCart( $manager, 1000 );

        $_token = $shopping->token();

        $this->assertNotEmpty( $_token );

        $this->assertTrue($manager->find( $_token ) instanceof ShoppingCart);
    }

    function test_manage_the_products_in_a_shopping_cart()
    {
        $manager = $this->getManager();
        $shopping = $this->getShoppingCart( $manager );

        $quantity = 10;
        $key = 1;

        $keyboard = $this->salable(['id' => 'k-1015', 'description' => 'keyboard', 'custom_property' => 'custom']);

        $this->assertTrue( $shopping->push($keyboard, $quantity));
        $item = $shopping->find( $keyboard->getItemKey( ) );

        $this->assertTrue($item instanceof SalableItem);

        $this->assertSame($item->getShortDescription(), 'keyboard');
        $this->assertSame($item->getKey(), 'k-1015');
        $this->assertSame($item->getProperty('custom_property'), 'custom');
        $this->assertNull($item->getProperty('non-existent'));

        $this->assertTrue($item->getSalable() instanceof $keyboard);
        $this->assertTrue($item->getQuantity() === $quantity );
        $this->assertTrue($shopping->count() === 1 );

        $this->assertFalse($shopping->remove( 'non-existent' ));
        $this->assertTrue($shopping->count() === 1 );

        $this->assertTrue($shopping->remove($item->getKey()));
        $this->assertTrue($shopping->count() === 0 );

        $this->assertTrue( $shopping->push($this->salable(['id' => $key]), $quantity));
        $this->assertTrue($shopping->count() === 1 );

        $this->assertFalse( $shopping->push($this->salable(['id' => $key])));
        $this->assertTrue($shopping->count() === 1 );
    }


    function test_correct_calculations_are_performed()
    {
        $manager = $this->getManager();
        $shopping = $this->getShoppingCart( $manager );
        $product = $this->salable(['price' => 36.99]);

        $shopping->setPaymentDocument(new Invoice);
        $this->assertTrue($shopping->push($product, 3));
        $this->assertSame($shopping->getSubtotal(), 110.97);
        $this->assertSame($shopping->getImpost(), 19.975);
        $this->assertSame($shopping->getDefinitiveTotal(), 130.945);

        $shopping->setPaymentDocument(new Voucher);
        $this->assertSame($shopping->getSubtotal(), 110.97);
        $this->assertSame($shopping->getImpost(), 0.0);
        $this->assertSame($shopping->getDefinitiveTotal(), 110.97);

        $this->assertTrue($shopping->remove($product->getItemKey()));

        $product = $this->discountableProduct(['id' => 'discountable', 'price' => 128.9, 'discount' => 10 ]);

        $shopping->setPaymentDocument(new Invoice);
        $this->assertTrue($shopping->push($product, 2));
        $this->assertSame($shopping->getSubtotal(), 257.8);
        $this->assertSame($shopping->getDiscount(), 25.78);
        $this->assertSame($shopping->getImpost(), 46.404);
        $this->assertSame($shopping->getDefinitiveTotal(), 278.424);

        $shopping->setPaymentDocument(new Voucher);
        $this->assertSame($shopping->getSubtotal(), 257.8);
        $this->assertSame($shopping->getDiscount(), 25.78);
        $this->assertSame($shopping->getImpost(), 0.0);
        $this->assertSame($shopping->getDefinitiveTotal(), 232.02);

        $product = $this->salable(['price' => 36.99]);

        $shopping->setPaymentDocument(new Invoice);
        $this->assertTrue($shopping->push($product, 3));
        $this->assertSame($shopping->getSubtotal(), 368.77);
        $this->assertSame($shopping->getDiscount(), 25.78);
        $this->assertSame($shopping->getImpost(), 66.379);
        $this->assertSame($shopping->getDefinitiveTotal(), 409.369);
    }


}