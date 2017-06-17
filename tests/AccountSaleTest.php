<?php
/**
 * Created by enea dhack - 17/06/17 04:29 PM
 */

namespace Enea\Tests;

use Enea\Cashier\SalableItem;

class AccountSaleTest extends TestCase
{

    function test_an_account_is_attached_to_a_purchase()
    {
        $manager = $this->getManager();

        $account = $this->getPreinvoice(['id' => 'preinvoice']);

        $shopping = $this->getShoppingCart($manager, 'client')->attach($account);

        $this->assertTrue( $shopping->getAccount() instanceof $account );
        $this->assertSame( $shopping->getAccount()->getKeyIdentification(), 'preinvoice' );

    }

    /**
     * Preinvoice - detail - total items 4
     * new PreinvoiceItem(['id' => 100, 'price' => 130.50, 'quantity' => 3, 'description' => 'some description', 'taxable' => true]),
     * new PreinvoiceItem(['id' => 101, 'price' => 530.30, 'quantity' => 1, 'description' => 'some description', 'taxable' => true]),
     * new PreinvoiceItem(['id' => 102, 'price' => 10.50, 'quantity' => 5, 'description' => 'some description', 'taxable' => true]),
     * new PreinvoiceItem(['id' => 103, 'price' => 30.40, 'quantity' => 2, 'description' => 'some description', 'taxable' => true]),
     * */
    function test_manage_account_detail_attached()
    {
        $manager = $this->getManager();

        $account = $this->getPreinvoice();

        $shopping = $this->getShoppingCart( $manager )->attach($account);

        $this->assertSame($shopping->storage()->count(), 4);
        $this->assertSame($shopping->collection()->count(), 0);

        $this->assertNull($shopping->find(100) );
        $this->assertTrue($shopping->pull(100));
        $this->assertSame($shopping->storage()->count(), 4);
        $this->assertSame($shopping->collection()->count(), 1);
        $this->assertTrue($shopping->find(100) instanceof SalableItem);

        $this->assertFalse($shopping->pull('non-existent'));
        $this->assertSame($shopping->storage()->count(), 4);
        $this->assertSame($shopping->collection()->count(), 1);

        $this->assertTrue($shopping->pull(101));
        $this->assertSame($shopping->storage()->count(), 4);
        $this->assertSame($shopping->collection()->count(), 2);
        $this->assertTrue($shopping->find(101) instanceof SalableItem);

        $shopping->detach( );
        $this->assertSame($shopping->storage()->count(), 0);
        $this->assertSame($shopping->collection()->count(), 0);

    }

}