<?php
/**
 * Created by enea dhack - 05/08/2020 21:27.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Contracts\TotalizableContract;
use Enea\Cashier\Helpers;
use Enea\Cashier\IsJsonable;
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

    public function getDiscount(): DiscountContract
    {
        return $this->discount;
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
        return array_merge($this->getDiscount()->toArray(), [
            'total' => $this->getTotal(),
        ]);
    }
}
