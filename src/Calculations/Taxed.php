<?php
/**
 * Created by enea dhack - 05/08/2020 21:26.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Contracts\TotalizableContract;
use Enea\Cashier\Helpers;
use Enea\Cashier\IsJsonable;
use Enea\Cashier\Modifiers\TaxContract;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;

class Taxed implements TotalizableContract, Arrayable, Jsonable, JsonSerializable
{
    use IsJsonable;

    private TaxContract $tax;

    private float $subtotal;

    public function __construct(TaxContract $tax, float $subtotal)
    {
        $this->tax = $tax;
        $this->subtotal = $subtotal;
    }

    public function getTax(): TaxContract
    {
        return $this->tax;
    }

    public function getTotal(): float
    {
        return Helpers::decimal(Percentager::excluded($this->subtotal, $this->tax->getPercentage())->calculate());
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge($this->getTax()->toArray(), [
            'total' => $this->getTotal(),
        ]);
    }
}