<?php
/**
 * Created by enea dhack - 30/05/2017 03:36 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\CartElementContract;
use Enea\Cashier\Contracts\DiscountableContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;

abstract class BaseItem implements Arrayable, Jsonable
{
    /**
     * Item quantity.
     *
     * @var int
     */
    private $quantity;

    /**
     * Old quantity.
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
     * Tax percentage.
     *
     * @var int
     * */
    protected $impostPercentage = Calculator::ZERO;

    /**
     * Plan discount percentage.
     *
     * @var int
     * */
    protected $planDiscountPercentage = Calculator::ZERO;

    /**
     * @var CartElementContract
     */
    protected $element;

    /**
     * BaseItem constructor.
     *
     * @param CartElementContract $element
     */
    public function __construct(CartElementContract $element)
    {
        $this->element = $element;
    }

    /**
     * Change quantity for item.
     *
     * @param int $quantity
     *
     * @return void
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->old_quantity = $this->old_quantity ?: $quantity;
        $this->recalculate = true;
    }

    /**
     * Returns item quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param string $property
     *
     * @return string|bool|int|array
     */
    public function getProperty($property)
    {
        return isset($this->getCustomProperties()[$property]) ? $this->getCustomProperties()[$property] : null;
    }

    /**
     *  Returns the model to calculate prices.
     *
     * @return Calculator
     */
    public function getCalculator()
    {
        if ($this->needToRecalculate()) {
            $this->calculator = $this->calculatorInstance();

            $model = $this->model();

            if ($model instanceof  DiscountableContract) {
                $this->calculator->setDiscountPercentage($model->getDiscountPercentage());
            }

            $this->calculator->setImpostPercentage($this->getImpostPercentage());
            $this->calculator->setPlanPercentage($this->planDiscountPercentage);
        }

        return $this->calculator;
    }

    /**
     * Set a plan discount for the item.
     *
     * @param int $percentage
     */
    public function setPlanDiscountPercentage($percentage)
    {
        $this->planDiscountPercentage = $percentage;
    }

    /**
     * Returns true in case the quantity of the item has been changed and is no longer the same as in the beginning.
     *
     * @return bool
     */
    public function isTouched()
    {
        return $this->old_quantity != $this->getQuantity();
    }

    /**
     * Returns item name.
     *
     * @return null|string
     * */
    public function getShortDescription()
    {
        return $this->element->getShortDescription();
    }

    /**
     * Returns an array with extra properties.
     *
     * @return array
     * */
    public function getCustomProperties()
    {
        return $this->element->getCustomProperties();
    }

    /**
     * Returns identification.
     *
     * @return int|string
     * */
    public function getKey()
    {
        return $this->element->getItemKey();
    }

    /**
     * Get base price for item.
     *
     * @return float
     */
    protected function getBasePrice()
    {
        return $this->element->getBasePrice();
    }

    /**
     * Verifies whether it is necessary to recalculate the price.
     *
     * @return bool
     */
    protected function needToRecalculate()
    {
        return $this->recalculate || empty($this->calculator);
    }

    /**
     * Returns the assigned tax.
     *
     * @return int
     */
    protected function getImpostPercentage()
    {
        return $this->impostPercentage;
    }

    /**
     * Return an instance of the model that represents the product.
     *
     * @return Model
     */
    protected function model()
    {
        return $this->element;
    }

    /**
     * Returns an instance of calculator.
     *
     * @return Calculator
     */
    private function calculatorInstance()
    {
        if (empty($path = config('cashier.calculator'))) {
            return new Calculator($this->getBasePrice(), $this->getQuantity());
        }

        return new $path($this->getBasePrice(), $this->getQuantity());
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->getCalculator()->toArray(), [
            'key' => $this->getKey(),
            'name' => $this->getShortDescription(),
            'properties' => $this->getCustomProperties(),
        ]);
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
