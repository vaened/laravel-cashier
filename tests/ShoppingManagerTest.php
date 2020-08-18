<?php
/**
 * Created by enea dhack - 17/08/2020 21:38.
 */

namespace Enea\Tests;

use Enea\Cashier\Facades\ShoppingManager;
use Enea\Cashier\Managers\ShoppingManagerContract;

class ShoppingManagerTest extends TestCase
{
    public function test_get_default_manage(): void
    {
        $this->assertInstanceOf(ShoppingManagerContract::class, ShoppingManager::getFacadeRoot());
    }
}
