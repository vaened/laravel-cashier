<?php
/**
 * Created by enea dhack - 30/05/2017 03:19 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\InvoiceContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

abstract class BaseManager implements Arrayable, Jsonable
{
    /**
     * Identification
     *
     * @var string
     * */
    protected $token;

    /**
     * Selected items
     *
     * @var  Collection
     * */
    private $collection;

    /**
     * @var int
     */
    protected $impostPercentage = Calculator::ZERO;



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
    public function getImpost( ): float
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
     * Returns the total discount
     *
     * @return float
     */
    public function getTotalDiscounts( ): float
    {
        return $this->collection->sum(function ( BaseItem $item ){
            return $item->getCalculator( )->getTotalDiscounts();
        });
    }


    /**
     * Returns the tax percentage
     *
     * @return int
     */
    public function getImpostPercentage( ): int
    {
        return $this->impostPercentage;
    }

    /**
     * set the payment document and extract tex percentage
     *
     * @param InvoiceContract $invoice
     *
     * @return int
     */
    public function setPaymentDocument( InvoiceContract $invoice )
    {
        $this->impostPercentage = $invoice->getTaxPercentageAttribute( );

        $this->collection()->each(function (BaseItem $item){
            $item->setImpostPercentage($this->getImpostPercentage( ));
        });
    }



    /**
     * Add a new item to the collection
     *
     * @param $key
     * @param BaseItem $item
     * @return void
     */
    protected function add( $key, BaseItem $item): void
    {
        $this->collection->put( $key, $item);
    }

    /**
     * Filter items that have not been marked as deleted
     * 
     * @return  Collection
     */
    public function collection( ): Collection
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

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'token' => $this->token(),
            'subtotal' => $this->getSubtotal(),
            'definitive_total' => $this->getDefinitiveTotal(),
            'impost' => $this->getImpost(),
            'discount' => $this->getDiscount(),
            'plan_discount' => $this->getPlanDiscount(),
            'total_discounts' => $this->getTotalDiscounts(),
            'impost_percentage' => $this->getImpostPercentage(),
            'elements' => $this->collection( )->toArray(),
        ];
    }

    /**
     * Returns only the value of the elements leaving aside the keys
     *
     * @return Collection
     */
    public function lists( ): Collection
    {
        return $this->collection( )->values( );
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @return string
     */
    public function token( ): string
    {
        return $this->token ?: $this->token = str_random(20);
    }

}