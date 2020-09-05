<?php
/**
 * Created by enea dhack - 31/07/2020 18:40.
 */

namespace Enea\Cashier\Calculations;

class Percentager
{
    private float $price;

    private float $percentage;

    private bool $included;

    public function __construct(float $price, float $percentage, bool $included)
    {
        $this->price = $price;
        $this->percentage = $percentage;
        $this->included = $included;
    }

    public static function excluded(float $price, float $percentage): self
    {
        return new static($price, $percentage, false);
    }

    public static function included(float $price, float $percentage): self
    {
        return new static($price, $percentage, true);
    }

    public function calculate(): float
    {
        if (! $this->included) {
            return $this->price * $this->percentage / 100;
        }

        return $this->price - $this->price / ($this->percentage / 100 + 1);
    }
}
