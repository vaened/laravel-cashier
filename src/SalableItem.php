<?php
/**
 * Created by enea dhack - 30/05/2017 03:30 PM
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\CountableStaticContract;
use Enea\Cashier\Contracts\SalableContract;
use Enea\Exceptions\IrreplaceableAmountException;
use Illuminate\Database\Eloquent\Model;

class SalableItem extends BaseItem
{

    /**
     * @var SalableContract
     */
    protected $salable;


    /**
     * SalableItem constructor.
     *
     * @param SalableContract $salable
     * @param int $quantity
     * @param int $impostPercentage
     */
    public function __construct( SalableContract $salable, int $quantity = null, int $impostPercentage = 0 )
    {
        if ( $salable instanceof  CountableStaticContract ) {

            if (! is_null($quantity)) {
                throw new IrreplaceableAmountException( $quantity );
            }

            $quantity = $salable ->getQuantity( );
        }

        $this->salable = $salable;
        parent::setQuantity($quantity);
        parent::setImpostPercentage($impostPercentage);
    }


    /**
     * Get base price for item
     *
     * @return float
     */
    protected function getBasePrice( ): float
    {
        return $this->salable->getBasePrice( );
    }

    /**
     * Return an instance of the model that represents the product
     *
     * @return Model
     */
    protected function model( ): Model
    {
        return $this->salable;
    }

    /**
     * Returns identification
     *
     * @return int|string
     * */
    public function getKey()
    {
        return $this->salable->getItemKey();
    }
}