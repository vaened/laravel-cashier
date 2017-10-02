<?php
/**
 * Created on 29/09/17 by enea dhack.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Helpers;
use Enea\Cashier\IsJsonable;
use Enea\Cashier\Modifiers\AmountModifierContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Modifier implements Arrayable, Jsonable
{
    use IsJsonable;

    /**
     * @var AmountModifierContract
     */
    protected $modifier;

    /**
     * @var float
     */
    protected $amount;

    /**
     * BaseModifier constructor.
     *
     * @param AmountModifierContract $modifier
     * @param float $amount
     */
    public function __construct(AmountModifierContract $modifier, $amount)
    {
        $this->modifier = $modifier;
        $this->amount = $amount;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     * @return void
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Returns the percentage to extract from the amount.
     *
     * @return int
     */
    public function getPercentage()
    {
        return $this->modifier->getPercentage();
    }

    /**
     * Returns the total extracted from the amount.
     *
     * @return float
     */
    public function getCleanTotalExtracted()
    {
        return $this->amount * $this->getFormatPercentage();
    }

    /**
     * Returns the total extracted with decimal format.
     *
     * @return float
     */
    public function getTotalExtracted()
    {
        return Helpers::decimalFormat($this->getCleanTotalExtracted());
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->modifier->toArray(), [
            'amount' => $this->amount,
            'percentage' => $this->getPercentage(),
            'extracted' => $this->getTotalExtracted(),
        ]);
    }

    /**
     * Returns the formatted percentage to extract from the amount.
     *
     * @return float
     */
    protected function getFormatPercentage()
    {
        return Helpers::toPercentage($this->getPercentage());
    }

    /**
     * Converts the percentage value to step by parameter.
     *
     * @param int $percentage
     * @return float
     */
    protected function toPercentage($percentage)
    {
        if (! is_float($percentage)) {
            return $percentage / 100;
        }

        return $percentage;
    }
}
