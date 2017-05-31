<?php
/**
 * Created by enea dhack - 30/05/2017 06:10 PM
 */

namespace Enea\Tests;


use Enea\Cashier\ShoppingCard;
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
    function you_can_add_a_new_item_to_the_list( )
    {
        $shopping = $this->getShoppingCard( );
        $this->assertTrue($shopping->push(new SalableModel(['id' => 1]), 1));
        $this->assertSame($shopping->count(), 1);
        $this->assertTrue($shopping->push(new SalableModel(['id' => 2]), 1));
        $this->assertSame($shopping->count(), 2);
    }

    /**
     * @test
     */
    function you_can_not_add_a_repeated_item_in_the_list( )
    {
        $shopping = $this->getShoppingCard( );
        $this->assertTrue($shopping->push(new SalableModel(['id' => 1]), 1));
        $this->assertSame($shopping->count(), 1);
        $this->assertFalse($shopping->push(new SalableModel(['id' => 1]), 1));
        $this->assertSame($shopping->count(), 1);
    }


    protected function getShoppingCard()
    {
        $buyer = new BuyerModel(['id' => self::BUYER ]);
        return new ShoppingCard($buyer);
    }

}