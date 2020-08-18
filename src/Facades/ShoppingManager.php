<?php
/**
 * Created by enea dhack - 12/06/17 10:54 PM.
 */

namespace Enea\Cashier\Facades;

use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Managers\ShoppingManagerContract;
use Enea\Cashier\ShoppingCart;
use Illuminate\Support\Facades\Facade;

/**
 * Class SessionShoppingManager.
 *
 * @author enea dhack <enea.so@live.com>
 *
 * @method static ShoppingCart initialize(BuyerContract $buyer, DocumentContract $document = null, array $taxes = [])
 * @method static ShoppingCart find(string $token)
 * @method static bool drop(string $token)
 * @method static void flush()
 *
 * @see \Enea\Cashier\Managers\ShoppingManagerContract
 */
class ShoppingManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws \RuntimeException
     *
     */
    protected static function getFacadeAccessor()
    {
        return ShoppingManagerContract::class;
    }
}
