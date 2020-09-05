<?php
/**
 * Created by enea dhack - 05/08/2020 21:27.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\{Helpers, IsJsonable};
use Enea\Cashier\Contracts\TotalizableContract;
use Enea\Cashier\Modifiers\DiscountContract;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;

class Discounted implements TotalizableContract, Arrayable, Jsonable, JsonSerializable
{
    use IsJsonable;

    private DiscountContract $discount;

    private float $subtotal;

    public function __construct(DiscountContract $discount, float $subtotal)
    {
        $this->discount = $discount;
        $this->subtotal = $subtotal;
    }

    public function getDiscountCode(): string
    {
        return $this->discount->getDiscountCode();
    }

    public function getDescription(): string
    {
        return $this->discount->getDescription();
    }

    public function getTotal(): float
    {
        return Helpers::decimal($this->discount->extract($this->subtotal));
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge($this->discount->toArray(), [
            'total' => $this->getTotal(),
        ]);
    }
}
