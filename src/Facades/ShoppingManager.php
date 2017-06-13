<?php
/**
 * Created by enea dhack - 12/06/17 10:54 PM
 */

namespace Enea\Cashier\Facades;


use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\InvoiceContract;
use Enea\Cashier\ShoppingCard;
use Illuminate\Support\Facades\Facade;
use \Enea\Cashier\ShoppingManager as Manager;

/**
 * Class ShoppingManager
 * @package Enea\Cashier\Facades
 * @author enea dhack <enea.so@live.com>
 *
 * Methods
 *
 * @method static ShoppingCard initialize( BuyerContract $buyer, InvoiceContract $invoice = null)
 * @method static ShoppingCard find( string $token )
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
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return Manager::class;
    }

}