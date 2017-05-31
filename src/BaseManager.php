<?php
/**
 * Created by enea dhack - 30/05/2017 03:19 PM
 */

namespace Enea\Cashier;


use Illuminate\Support\Collection;

abstract class BaseManager
{

    /**
     * Selected items
     *
     * @var  Collection
     * */
    protected $collection;


    public function __construct( )
    {
        $this->clean( );
    }

    /**
     * Build subtotal items
     *
     * @return float
     */
    public function getSubtotal( ): float
    {
        return $this->collection->sum(function ( BaseItem $item ){
            return $item->getCalculator( )->getSubtotal();
        });
    }

    /**
     * Build definite total
     *
     * @return float
     */
    public function getDefinitiveTotal( ): float
    {
        return $this->collection->sum(function ( BaseItem $item ) {
            return $item->getCalculator( )->getDefinitiveTotal();
        });
    }

    /**
     * Build total tax
     *
     * @return float
     */
    public function getGeneralSaleTax( ): float
    {
        return $this->collection->sum(function ( BaseItem $item ){
            return $item->getCalculator( )->getImpost();
        });
    }

    /**
     * Returns the discount applied to the item
     *
     * @return float
     */
    public function getDiscount( ): float
    {
        return $this->collection->sum(function ( BaseItem $item ){
            return $item->getCalculator( )->getDiscount();
        });
    }

    /**
     * @return float
     */
    public function getPlanDiscount( ): float
    {
        return $this->collection->sum(function ( BaseItem $item ){
            return $item->getCalculator( )->getPlanDiscount( );
        });
    }


    /**
     * @return float
     */
    public function getTotalDiscounts( ): float
    {
        return $this->collection->sum(function ( BaseItem $item ){
            return $item->getCalculator( )->getTotalDiscounts();
        });
    }


    /**
     * Filter items that have not been marked as deleted
     * 
     * @return  Collection
     */
    protected function collection( ): Collection
    {
        return $this->collection;
    }


    /**
     * Clean the collection
     *
     * @return  void
     * */
    public function clean( ): void
    {
        $this->collection = new Collection( );
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count( ): int
    {
        return $this->collection()->count();
    }
}