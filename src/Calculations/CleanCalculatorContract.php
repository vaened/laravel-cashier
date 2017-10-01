<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Calculations;

interface CleanCalculatorContract
{
    /**
     * Returns the unit price.
     *
     * @return float
     */
    public function getCleanBasePrice();

    /**
     * Multiply the total by the amount.
     *
     * @return float
     */
    public function getCleanSubtotal();

    /**
     * Returns total discounts.
     *
     * @return float
     */
    public function getCleanDiscounts();

    /**
     * Returns total tax payable.
     *
     * @return float
     */
    public function getCleanTaxes();

    /**
     * Returns the definitive total.
     *
     * @return float
     */
    public function getCleanDefinitiveTotal();
}
