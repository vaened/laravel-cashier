<?php
/**
 * Created by enea dhack - 30/05/2017 03:36 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\{CountableStaticContract, DiscountableContract};
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
     * Tax percentage
     *
     * @var int
     * */
    protected $impostPercentage = Calculator::ZERO;

    /**
     * Plan discount percentage
     *
     * @var int
     * */
    protected $planDiscountPercentage = Calculator::ZERO;

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

            $quantity = $model->getQuantity( );
        }

        $this->setValidQuantity( $quantity );
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

            $this->calculator->setImpostPercentage( $this->impostPercentage );
            $this->calculator->setPlanPercentage( $this->planDiscountPercentage );
        }

        return $this->calculator;
    }

    /**
     * Set a tax rate for the item
     *
     * @param int $percentage
     */
    public function setImpostPercentage( int $percentage): void
    {
        $this->impostPercentage = $percentage;
    }

    /**
     * Set a plan discount for the item
     *
     * @param int $percentage
     */
    public function setPlanDiscountPercentage( int $percentage): void
    {
        $this->planDiscountPercentage = $percentage;
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
    protected abstract function calculatorConfiguration( ): Calculator;

    /**
     * Return an instance of the model that represents the product
     *
     * @return Model
     */
    protected abstract function model( ): Model;

}