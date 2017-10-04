<?php
/**
 * Created by enea dhack - 30/05/2017 03:36 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Calculations\Calculator;
use Enea\Cashier\Calculations\CalculatorContract;
use Enea\Cashier\Calculations\Modifier;
use Enea\Cashier\Contracts\AttributableContract;
use Enea\Cashier\Contracts\CartElementContract;
use Enea\Cashier\Contracts\DiscountableContract;
use Enea\Cashier\Modifiers\DiscountContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

abstract class BaseItem implements Arrayable, Jsonable, AttributableContract
{
    use IsJsonable, HasAttributes;

    /**
     * Custom attributes.
     *
     * @var Collection<string, mixed>
     */
    protected $additionalAttributes;

    /**
     * Element added to the handler.
     *
     * @var CartElementContract
     */
    protected $element;

    /**
     * Contains the instance of the calculator.
     *
     * @var CalculatorContract
     * */
    private $calculator;

    /**
     * BaseItem constructor.
     *
     * @param CartElementContract $element
     * @param int $quantity
     */
    public function __construct(CartElementContract $element, $quantity)
    {
        $this->element = $element;
        $this->initialize();
        $this->makeCalculator($element->getBasePrice(), $quantity);
        $this->verifyDiscount();
    }

    /**
     * Returns the amount calculator.
     *
     * @return CalculatorContract
     */
    public function getCalculator()
    {
        return $this->calculator;
    }

    /**
     * Returns item quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->getCalculator()->getQuantity();
    }

    /**
     * Returns the identification key of the element.
     *
     * @return int|string
     */
    public function getElementKey()
    {
        return $this->getElement()->getItemKey();
    }

    /**
     * Returns all discounts.
     *
     * @return Collection<Modifier>
     */
    public function getDiscounts()
    {
        return $this->getCalculator()->getDiscounts();
    }

    /**
     * Returns a discount by code.
     *
     * @param $code
     * @return Modifier
     */
    public function getDiscount($code)
    {
        return $this->getCalculator()->getDiscount($code);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge($this->getCalculator()->toArray(), [
            'key' => $this->getElementKey(),
            'name' => $this->getElement()->getShortDescription(),
            'properties' => $this->getAdditionalAttributes()->toArray(),
        ]);
    }

    /**
     * {@inheritdoc}
     * */
    public function getAdditionalAttributes()
    {
        return $this->additionalAttributes->merge($this->getElement()->getAdditionalAttributes());
    }

    /**
     * Returns the element added to the handler.
     *
     * @return CartElementContract
     */
    protected function getElement()
    {
        return $this->element;
    }

    /**
     * Verifies if the item has a discount and applies it.
     *
     * @return void
     */
    protected function verifyDiscount()
    {
        if ($this->isDiscountable()) {
            /** @var DiscountableContract $element */
            $element = $this->element;
            $element->getDiscounts()->each(function (DiscountContract $discount) {
                $this->getCalculator()->addDiscount($discount);
            });
        }
    }

    /**
     * Returns true if the salable item is discountable,.
     *
     * @return bool
     */
    protected function isDiscountable()
    {
        return $this->element instanceof DiscountableContract;
    }

    /**
     * Build a calculator instance.
     *
     * @param $basePrice
     * @param $quantity
     * @return void
     */
    protected function makeCalculator($basePrice, $quantity)
    {
        $this->calculator = new Calculator($basePrice, $quantity);
    }

    /**
     * Initialize variables.
     *
     * @return void
     */
    protected function initialize()
    {
        $this->additionalAttributes = collect();
    }
}
