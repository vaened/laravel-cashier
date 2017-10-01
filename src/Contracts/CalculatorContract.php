<?php
/**
 * Created by enea dhack - 16/06/17 09:05 PM.
 */

namespace Enea\Cashier\Contracts;

use Enea\Cashier\Calculations\Modifier;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * Interface CalculatorContract.
 */
interface CalculatorContract extends Arrayable, Jsonable
{
    /**
     * Returns all taxes.
     *
     * @return Collection
     */
    public function getTaxes();

    /**
     * Returns all discounts.
     *
     * @return Collection
     */
    public function getDiscounts();

    /**
     * Returns the requested quantity for the item.
     *
     * @return int
     */
    public function getQuantity();

    /**
     * Returns the unit price with configured decimal format.
     *
     * @return float
     */
    public function getBasePrice();

    /**
     * Returns the subtotal with configured decimal format.
     *
     * @return float
     */
    public function getSubtotal();

    /**
     * Returns the discount total with configured decimal format.
     *
     * @return float
     */
    public function getTotalDiscounts();

    /**
     * Returns the tax total with configured decimal format.
     *
     * @return float
     */
    public function getTotalTaxes();

    /**
     * Returns the definitive total with configured decimal format
     *
     * @return float
     */
    public function getDefinitiveTotal();

    /**
     * Returns a discount located by its code.
     *
     * @param string|int $code
     * @return Modifier|null
     */
    public function getDiscount($code);
}
