<?php
/**
 * Created by enea dhack - 12/06/17 10:54 PM.
 */

namespace Enea\Cashier\Facades;

use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\ShoppingCart;
use Enea\Cashier\ShoppingManager as Manager;
use Illuminate\Support\Facades\Facade;

/**
 * Class ShoppingManager.
 *
 * @author enea dhack <enea.so@live.com>
 *
 * Methods
 *
 * @method static ShoppingCart initialize( BuyerContract $buyer, DocumentContract $invoice = null)
 * @method static ShoppingCart find( string $token )
 * @method static bool drop( string $token )
 * @method static void flush( )
 *
 * @see \Enea\Cashier\ShoppingManager
 */
class ShoppingManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Manager::class;
    }
}
