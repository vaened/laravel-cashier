<?php
/**
 * Created by enea dhack - 30/05/2017 03:36 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\CountableStaticContract;
use Enea\Cashier\Contracts\DiscountableContract;
use Enea\Exceptions\IrreplaceableAmountException;
use Illuminate\Database\Eloquent\Model;

abstract class BaseItem
{

    /**
     * Item quantity
     *
     * @var int
     */
    private $quantity;

    /**
     * Old quantity
     *
     * @var int
     * */
    protected $old_quantity;

    /**
     * @var Calculator
     * */
    protected $calculator;

    /**
     * @var bool
     * */
    protected $recalculate = false;

    /**
     * Change quantity for item
     *
     * @param int $quantity
     * @return void
     */
    public function setQuantity( ? int $quantity ): void
    {
        $model = $this->model( );

        if ( $model instanceof  CountableStaticContract ) {

            if (! is_null($quantity)) {
                throw new IrreplaceableAmountException( $quantity );
            }

            $quantity = $model->quantityAttribute( );
        }

        $this->setValidQuantity( $quantity );

    }

    /**
     * Establishes an amount for the item if it is valid
     *
     * @param int $quantity
     * @return void
     */
    private function setValidQuantity( int $quantity )
    {
        $this->quantity = $quantity;
        $this->old_quantity = $this->old_quantity ?: $quantity;
        $this->recalculate = true;
    }

    /**
     * Returns item quantity
     *
     * @return int
     */
    public function getQuantity( ): int
    {
        return $this->quantity;
    }

    /**
     *  Returns the model to calculate prices
     *
     * @return Calculator
     */
    public function getCalculator( ): Calculator
    {
        if ($this->needToRecalculate( )) {
            $this->calculator = $this->calculatorConfiguration( );

            $model = $this->model( );

            if ( $model instanceof  DiscountableContract) {
                $this->calculator->setDiscountPercentage($model->getDiscountPercentageAttribute());
            }

        }

        return $this->calculator;
    }

    /**
     * Returns true in case the quantity of the item has been changed and is no longer the same as in the beginning
     *
     * @return bool
     */
    public function isTouched(): bool
    {
        return $this->old_quantity != $this->getQuantity( );
    }

    /**
     * Verifies whether it is necessary to recalculate the price
     *
     * @return bool
     */
    protected function needToRecalculate( ) : bool
    {
        return $this->recalculate || empty($this->calculator);
    }

    /**
     * Returns the object of the calculator with the requested parameters
     *
     * @return Calculator
     */
    protected abstract function calculatorConfiguration(): Calculator;

    /**
     * Return an instance of the model that represents the product
     *
     * @return Model
     */
    protected abstract function model(): Model;

}