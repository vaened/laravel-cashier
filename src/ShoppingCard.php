<?php
/**
 * Created by enea dhack - 30/05/2017 03:31 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\{
    BuyerContract, DetailedContract, InvoiceContract, SalableContract
};

class ShoppingCard extends BaseManager
{

    /**
     * @var BuyerContract
     */
    protected $buyer;

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
        $item = new SalableItem( $salable, $quantity, $this->getImpostPercentage( ) );

        if ( $has = ! $this->hasItem( $salable->getItemKey( ) ) ) {
            $this->add($salable->getItemKey( ), $item);
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
     * Dump all elements of the database in a collection for later visualization or modification
     *
     * @return void
     */
    protected function buildElements( ): void
    {
        if ($this->buyer instanceof DetailedContract) {
            $this->buyer->getElements()->each(function ( SalableContract $element ) {
                $this->addElementItem( $element );
            });
        }
    }

    /**
     * Adds an item to the collection for later deletion or display
     *
     * @param SalableContract $element
     * @return void
     */
    protected function addElementItem(SalableContract $element ): void
    {
        $this->add($element->getItemKey( ), new SalableItem( $element, null,$this->getImpostPercentage( ) ));
    }

}