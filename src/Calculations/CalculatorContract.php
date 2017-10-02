<?php
/**
 * Created by enea dhack - 16/06/17 09:05 PM.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Modifiers\DiscountContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * Interface CalculatorContract.
 */
interface CalculatorContract extends Arrayable, Jsonable, CleanCalculatorContract
{
    /**
     * Set quantity.
     *
     * @param int $quantity
     * @return void
     */
    public function setQuantity($quantity);

    /**
     * Set multiple taxes.
     *
     * @param Collection $taxes
     * @return void
     */
    public function setTaxes(Collection $taxes);

    /**
     * Returns all taxes.
     *
     * @return Collection
     */
    public function getTaxes();

    /**
     * Returns all discounts.
     *
     * @return Collection<Modifier>
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
     * Adds a discount to the discount collection.
     *
     * @param DiscountContract $discount
     * @return void
     */
    public function addDiscount(DiscountContract $discount);

    /**
     * Remove a discount from the discount collection.
     *
     * @param string|int $code
     * @return void
     */
    public function removeDiscount($code);

    /**
     * Returns a discount located by its code.
     *
     * @param string|int $code
     * @return Modifier|null
     */
    public function getDiscount($code);
}
