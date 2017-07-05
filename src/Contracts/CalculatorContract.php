<?php
/**
 * Created by enea dhack - 16/06/17 09:05 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface CalculatorContract.
 */
interface CalculatorContract extends Arrayable
{
    /**
     * Returns the requested quantity for the item.
     *
     * @return int
     */
    public function getQuantity();

    /**
     * Returns the assigned tax.
     *
     * @return int
     */
    public function getImpostPercentage();

    /**
     * Returns the assigned discount item.
     *
     * @return int
     */
    public function getDiscountPercentage();

    /**
     * Returns the assigned plan discount item.
     *
     * @return int
     */
    public function getPlanDiscountPercentage();

    /**
     * Returns the assigned discount item.
     *
     * @param int $percentage
     *
     * @return CalculatorContract
     */
    public function setDiscountPercentage($percentage);

    /**
     * Set a tax rate for the item.
     *
     * @param int $percentage
     *
     * @return CalculatorContract
     */
    public function setImpostPercentage($percentage);

    /**
     * Set a plan discount for the item.
     *
     * @param int $percentage
     *
     * @return CalculatorContract
     */
    public function setPlanPercentage($percentage);

    /**
     * Returns the unit price.
     *
     * @return float
     */
    public function getBasePrice();

    /**
     * Multiply the total by the amount.
     *
     * @return float
     */
    public function getSubtotal();

    /**
     * Returns discount item.
     *
     * @return float
     */
    public function getDiscount();

    /**
     * Returns plan discount.
     *
     * @return float
     */
    public function getPlanDiscount();

    /**
     * Total sum of discounts.
     *
     * @return float
     */
    public function getTotalDiscounts();

    /**
     * Get general sale sax.
     *
     * @return float
     */
    public function getImpost();

    /**
     * Returns total definitive.
     *
     * @return float
     */
    public function getDefinitiveTotal();
}
