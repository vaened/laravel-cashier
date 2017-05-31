<?php
/**
 * Created by enea dhack - 30/05/2017 03:31 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\{BuyerContract, SalableContract};

class ShoppingCard extends BaseManager
{

    /**
     * @var BuyerContract
     *
     */
    protected $buyer;

    /**
     * ShoppingCard constructor.
     * @param BuyerContract $buyer
     */
    public function __construct( BuyerContract $buyer )
    {
        parent::__construct( );
        $this->buyer = $buyer;
    }

    /**
     * Add a new salable item to the collection and return true if it was successful
     *
     * @param SalableContract $salable
     * @param int $quantity
     * @return bool
     */
    public function push( SalableContract $salable, int $quantity): bool
    {
        $item = new SalableItem( $salable, $quantity );

        if ( $has = ! $this->hasItem( $salable->getItemKey( ) ) ) {
            $this->collection( )->put( $salable->getItemKey( ), $item);
        }

        return $has;
    }


    /**
     * Returns a item by identification
     *
     * @param string|int $key
     * @return SalableItem|null
     */
    public function find( $key ): ?SalableItem
    {
        return $this->collection()->get( $key );
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param $key
     * @return bool
     */
    public function hasItem($key )
    {
        return isset($this->collection( )[$key]);
    }

    /**
     * Returns buyer instance
     *
     * @return BuyerContract
     */
    public function buyer( ): BuyerContract
    {
        return $this->buyer;
    }
}