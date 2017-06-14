<?php
/**
 * Created by enea dhack - 30/05/2017 03:31 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\{
    BuyerContract, DetailedStaticContract, InvoiceContract, SalableContract
};
use Enea\Cashier\Exceptions\IrreplaceableDetailItemException;
use Illuminate\Support\Collection;

class ShoppingCard extends BaseManager
{

    /**
     * @var BuyerContract
     */
    protected $buyer;

    /**
     * @var bool
     * */
    protected $isStatic;

    /**
     * ShoppingCard constructor.
     * @param BuyerContract $buyer
     * @param InvoiceContract $invoice
     */
    public function __construct( BuyerContract $buyer, InvoiceContract $invoice = null)
    {
        parent::__construct( );
        $this->buyer = $buyer;

        if ( ! is_null( $invoice ) ) {
            $this->setPaymentDocument( $invoice );
        }

        $this->buildElements( );
    }

    /**
     * Add a new salable item to the collection and return true if it was successful
     *
     * @param SalableContract $salable
     * @param int $quantity
     * @return bool
     */
    public function push( SalableContract $salable, int $quantity = null ): bool
    {
        if( $this->isDetailedStatic( )) {
            throw new IrreplaceableDetailItemException( );
        }

        $item = new SalableItem( $salable, $quantity, $this->getImpostPercentage( ) );

        if ( $has = ! $this->hasItem( $salable->getItemKey( ) ) ) {
            $this->add($salable->getItemKey( ), $item);
        }

        return $has;
    }

    /**
     * Passes an item from the store to the collection and returns true on success
     *
     * @param string $key
     * @return bool
     */
    public function pass( String $key ): bool
    {
        if ( $has = $this->storage->has($key)) {
            $this->add($key, $this->storage->get($key));
        }

        return $has;
    }

    /**
     * Dump the storage in the collection of items
     *
     * @return ShoppingCard
     */
    public function dumpAllStorage( ): ShoppingCard
    {
        $this->storage->each(function( SalableItem $salable ) {
            $this->add($salable->getKey(), $salable);
        });

        return $this;
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
    public function hasItem( $key )
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

    /**
     * Returns storage
     *
     * @return Collection
     * */
    public function storage( ): Collection
    {
        return $this->storage;
    }

    /**
     * Dump all elements of the database in a collection for later visualization or modification
     *
     * @return void
     */
    protected function buildElements( ): void
    {
        if ($this->isDetailedStatic( )) {
            $this->buyer->getElements( )->each(function ( SalableContract $element ) {
                $this->storage->put( $element->getItemKey(), new SalableItem( $element, null,$this->getImpostPercentage( ) ));
            });
        }
    }

    /**
     * Returns true in case the header has a static detail
     *
     * @return bool
     */
    protected function isDetailedStatic()
    {
        return is_null( $this->isStatic ) ? $this->isStatic = $this->buyer instanceof DetailedStaticContract : $this->isStatic;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray( )
    {
        return array_merge( parent::toArray( ), [
            'buyer' => $this->buyer( )->toArray( ),
            'storage' => $this->storage()->toArray( )
        ]);
    }


}