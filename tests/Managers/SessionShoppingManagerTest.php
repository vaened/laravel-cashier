<?php
/**
 * Created by enea dhack - 17/08/2020 21:04.
 */

namespace Enea\Tests\Managers;

use Enea\Cashier\Managers\ShoppingManagerContract;
use Enea\Tests\DataBaseTestCase;
use Enea\Tests\Models\Client;

class SessionShoppingManagerTest extends DataBaseTestCase
{
    public function test_initialize_a_shopping_cart(): void
    {
        $manager = $this->getShoppingManager();
        $shoppingCart = $manager->initialize(Client::find(1));

        $this->assertNotNull($manager->find($shoppingCart->getGeneratedToken()));
    }

    public function test_drop_a_shopping_cart(): void
    {
        $manager = $this->getShoppingManager();
        $shoppingCart = $manager->initialize(Client::find(1));
        $manager->drop($shoppingCart->getGeneratedToken());

        $this->assertNull($manager->find($shoppingCart->getGeneratedToken()));
    }

    public function test_delete_all_shopping_carts(): void
    {
        $manager = $this->getShoppingManager();
        $customerShoppingCart1 = clone $manager->initialize(Client::find(1));
        $customerShoppingCart2 = clone $manager->initialize(Client::find(2));
        $manager->flush();

        $this->assertNull($manager->find($customerShoppingCart1->getGeneratedToken()));
        $this->assertNull($manager->find($customerShoppingCart2->getGeneratedToken()));
    }

    public function getShoppingManager(): ShoppingManagerContract
    {
        return app(ShoppingManagerContract::class);
    }
}
