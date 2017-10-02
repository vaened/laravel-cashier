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
     * Returns the percentage to extract from the amount.
     *
     * @return int
     */
    public function getModifierValue()
    {
        return $this->modifier->getModifierValue();
    }

    /**
     * Returns the total extracted from the amount.
     *
     * @return float
     */
    public function getCleanTotalExtracted()
    {
        if ($this->modifier->isPercentage()) {
            return $this->amount * $this->getFormatValue();
        }

        return $this->getFormatValue();
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
            'modifier' => $this->getModifierValue(),
            'extracted' => $this->getTotalExtracted(),
        ]);
    }

    /**
     * Returns the formatted percentage to extract from the amount.
     *
     * @return float
     */
    protected function getFormatValue()
    {
        if ($this->modifier->isPercentage()) {
            return Helpers::toPercentage($this->modifier->getModifierValue());
        }

        return $this->modifier->getModifierValue();
    }
}
