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
     */
    public function __construct( SalableContract $salable, int $quantity = null )
    {
        if ( $salable instanceof  CountableStaticContract ) {

            if (! is_null($quantity)) {
                throw new IrreplaceableAmountException( $quantity );
            }

            $quantity = $salable ->getQuantity( );
        }

        $this->salable = $salable;
        parent::setQuantity($quantity);
    }


    /**
     * Returns the object of the calculator with the requested parameters
     *
     * @return Calculator
     */
    protected function calculatorConfiguration(): Calculator
    {
        return new Calculator($this->salable->getBasePriceAttribute( ), $this->getQuantity( ));
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

}