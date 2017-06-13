<?php
/**
 * Created by enea dhack - 30/05/2017 06:10 PM
 */

namespace Enea\Tests;


use Enea\Cashier\Contracts\InvoiceContract;
use Enea\Cashier\ShoppingCard;
use Enea\Cashier\Taxes\Invoice;
use Enea\Tests\Models\BuyerModel;
use Enea\Tests\Models\Sales\SalableModel;

class SalesTest extends TestCase
{
    protected const BUYER = 50;

    /**
     * @test
     */
    function can_be_assigned_a_purchased()
    {
        $shopping = $this->getShoppingCard( );
        $this->assertSame($shopping->buyer( )->getBuyerKey(), self::BUYER);
    }

    /**
     * @test
     */
    function can_add_a_new_item_to_the_list( )
    {
        $shopping = $this->getShoppingCard( );
        $this->assertTrue($shopping->push(new SalableModel(['id' => 1, 'price' => 100]), 1));
        $this->assertSame($shopping->count(), 1);
        $this->assertTrue($shopping->push(new SalableModel(['id' => 2]), 1));
        $this->assertSame($shopping->count(), 2);
    }

    /**
     * @test
     */
    function can_not_add_a_repeated_item_in_the_list( )
    {
        $shopping = $this->getShoppingCard( );
        $this->assertTrue($shopping->push(new SalableModel(['id' => 1]), 1));
        $this->assertSame($shopping->count(), 1);
        $this->assertFalse($shopping->push(new SalableModel(['id' => 1]), 1));
        $this->assertSame($shopping->count(), 1);
    }

    /**
     * @test
     */
    function can_find_an_item()
    {
        $shopping = $this->getShoppingCard( );
        $shopping->push(new SalableModel(['id' => 56]), 5);

        $this->assertSame($shopping->find(56)->getKey(), 56);
        $this->assertSame($shopping->find(56)->getQuantity(), 5);
    }

    /**
     * @test
     */
    function can_not_find_an_item()
    {
        $shopping = $this->getShoppingCard( );
        $this->assertSame($shopping->find(56),  null);
    }

    /**
     * @test
     */
    function the_calculations_are_performed_well( )
    {
        $shopping = $this->getShoppingCard( );

        $shopping->push(new SalableModel(['id' => 10, 'price' => 100]), 2);

        $this->assertSame($shopping->getSubtotal(), 200.0);
        $this->assertSame($shopping->getDiscount(), 0.0);
        $this->assertSame($shopping->getTotalDiscounts(), 0.0);
        $this->assertSame($shopping->getImpost(), 0.0);

        $this->assertSame($shopping->getDefinitiveTotal(), 200.0);
        $this->assertSame($shopping->getImpostPercentage(), 0);

        $shopping->setPaymentDocument( new Invoice() );

        $this->assertSame($shopping->getSubtotal(), 200.0);
        $this->assertSame($shopping->getDiscount(), 0.0);
        $this->assertSame($shopping->getTotalDiscounts(), 0.0);
        $this->assertSame($shopping->getImpost(), 36.0);

        $this->assertSame($shopping->getDefinitiveTotal(), 236.0);
        $this->assertSame($shopping->getImpostPercentage(), 18);
    }

    protected function getShoppingCard( InvoiceContract $invoice = null )
    {
        $buyer = new BuyerModel(['id' => self::BUYER ]);
        return new ShoppingCard($buyer, $invoice);
    }

}