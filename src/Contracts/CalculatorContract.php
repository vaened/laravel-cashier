<?php
/**
 * Created by enea dhack - 16/06/17 09:05 PM
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface CalculatorContract
 * @package Enea\Cashier\Contracts
 *
 * Calculates all the amounts in the package, if you want to create a different logic to
 * calculate the balances, you must implement this interface or extend the calculator class
 * that comes by default in the package
 */
interface CalculatorContract extends Arrayable
{
    /**
     * Returns the requested quantity for the item
     *
     * @return int
     */
    public function getQuantity( ): int;

    /**
     * Returns the assigned tax
     *
     * @return int
     */
    public function getImpostPercentage( ): int;

    /**
     * Returns the assigned discount item
     *
     * @return int
     */
    public function getDiscountPercentage(): int;

    /**
     * Returns the assigned plan discount item
     *
     * @return int
     */
    public function getPlanDiscountPercentage(): int;

    /**
     * Returns the assigned discount item
     *
     * @param int $percentage
     * @return CalculatorContract
     */
    public function setDiscountPercentage( int $percentage ): CalculatorContract;

    /**
     * Set a tax rate for the item
     *
     * @param int $percentage
     * @return CalculatorContract
     */
    public function setImpostPercentage( int $percentage ): CalculatorContract;

    /**
     * Set a plan discount for the item
     *
     * @param int $percentage
     * @return CalculatorContract
     */
    public function setPlanPercentage( int $percentage ): CalculatorContract;

    /**
     * Returns the unit price
     *
     * @return float
     */
    public function getBasePrice( ): float;

    /**
     * Multiply the total by the amount
     *
     * @return float
     */
    public function getSubtotal( ): float;

    /**
     * Returns discount item
     *
     * @return float
     */
    public function getDiscount( ): float;

    /**
     * Returns plan discount
     *
     * @return float
     */
    public function getPlanDiscount( ): float;

    /**
     * Total sum of discounts
     *
     * @return float
     */
    public function getTotalDiscounts( ): float;

    /**
     * Get general sale sax
     *
     * @return float
     */
    public function getImpost( ): float;

    /**
     * Returns total definitive
     *
     * @return float
     */
    public function getDefinitiveTotal( ): float;

}