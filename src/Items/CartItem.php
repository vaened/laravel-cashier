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
use Enea\Cashier\Contracts\KeyableContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class CartElement implements Arrayable, Jsonable, AttributableContract, KeyableContract
{
    use IsJsonable, HasAttributes;

    protected CartElementContract $element;

    protected array $additionalAttributes;

    private CalculatorContract $calculator;

    public function __construct(CartElementContract $element, int $quantity)
    {
        $this->element = $element;
        $this->additionalAttributes = $element->getAdditionalAttributes();
        $this->calculator = new Calculator($element, $quantity);
        $this->applyDiscountsFrom($element);
    }

    public function getCalculator(): CalculatorContract
    {
        return $this->calculator;
    }

    public function getQuantity(): int
    {
        return $this->getCalculator()->getQuantity();
    }

    public function getUniqueIdentificationKey(): string
    {
        return $this->element->getUniqueIdentificationKey();
    }

    public function getDiscounts(): array
    {
        return $this->getCalculator()->getDiscounts();
    }

    /**
     * Returns a discount by code.
     *
     * @param $code
     * @return Modifier
     */
    public function getDiscount(string $code)
    {
        return $this->getCalculator()->getDiscount($code);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge($this->getCalculator()->toArray(), [
            'key' => $this->element->getUniqueIdentificationKey(),
            'name' => $this->element->getShortDescription(),
            'properties' => $this->additionalAttributes,
        ]);
    }

    public function getAdditionalAttributes(): array
    {
        return $this->additionalAttributes;
    }

    protected function applyDiscountsFrom(CartElementContract $element): void
    {
        if ($element instanceof DiscountableContract) {
            foreach ($element->getDiscounts() as $discount) {
                $this->getCalculator()->addDiscount($discount);
            }
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
}
