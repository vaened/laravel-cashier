<?php
/**
 * Created by enea dhack - 30/05/2017 03:36 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\DiscountableContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;

abstract class BaseItem implements Arrayable, Jsonable
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
    public function setQuantity( int $quantity ): void
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

            $this->calculator = $this->calculator( );

            $model = $this->model( );

            if ( $model instanceof  DiscountableContract) {
                $this->calculator->setDiscountPercentage($model->getDiscountPercentageAttribute( ));
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
        $this->recalculate = true;

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
    public function isTouched( ): bool
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
     * Returns an instance of calculator
     *
     * @return Calculator
     */
    private function calculator( ): Calculator
    {
        if ( empty($path = config('cashier.calculator'))) {
            return new Calculator($this->getBasePrice(), $this->getQuantity());
        }

        return new $path($this->getBasePrice(), $this->getQuantity());
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray( )
    {
        return $this->getCalculator( )->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Returns identification
     *
     * @return int|string
     * */
    public abstract function getKey();

    /**
     * Get base price for item
     *
     * @return float
     */
    protected abstract function getBasePrice( ): float ;

    /**
     * Return an instance of the model that represents the product
     *
     * @return Model
     */
    protected abstract function model( ): Model;

}