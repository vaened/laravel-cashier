<?php
/**
 * Created by enea dhack - 13/06/2017 08:44 PM
 */

namespace Enea\Tests;

use Enea\Cashier\ShoppingCard;
use Enea\Cashier\ShoppingManager;
use Enea\Tests\Models\BuyerModel;

class ShoppingManagerTest extends TestCase
{

    protected function getBuyer( ): BuyerModel
    {
        return new BuyerModel(['id' => 1 ]);
    }

    protected function getShoppingManager( ): ShoppingManager
    {
        return new ShoppingManager($this->app['session']);
    }

    /**
     * @test
     * */
    function can_add_a_shopping_cart_for_the_first_time()
    {
        $manager =  $this->getShoppingManager( );

        $shopping = $manager->initialize($this->getBuyer());

        $_token = $shopping->token( );

        $this->assertTrue($manager->find($_token) instanceof ShoppingCard);
        $this->assertFalse($manager->find( 'nonexistent' ) instanceof ShoppingCard);
    }

    /**
     * @test
     */
    function can_add_multiple_shopping_carts_in_the_session()
    {
        $manager =  $this->getShoppingManager( );

        $shopping = $manager->initialize($this->getBuyer());

        $_token = $shopping->token( );

        $this->assertTrue($manager->find($_token) instanceof ShoppingCard);

        $shopping = $manager->initialize($this->getBuyer());

        $_token = $shopping->token( );

        $this->assertTrue($manager->find($_token) instanceof ShoppingCard);

        $this->assertFalse($manager->find( 'nonexistent' ) instanceof ShoppingCard);
    }

}