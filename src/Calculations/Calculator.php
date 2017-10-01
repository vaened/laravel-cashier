<?php
/**
 * Created by enea dhack - 30/05/2017 02:54 PM.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Contracts\CalculatorContract;
use Enea\Cashier\Helpers;
use Enea\Cashier\IsJsonable;
use Enea\Cashier\Modifiers\AmountModifierContract;
use Enea\Cashier\Modifiers\DiscountContract;
use Enea\Cashier\Modifiers\TaxContract;
use Illuminate\Support\Collection;

/**
 * Class Calculator.
 *
 * @author enea dhack <enea.so@live.com>
 */
class Calculator implements CalculatorContract
{
    use IsJsonable;

    /**
     * Tax excluder included.
     *
     * @var Excluder
     */
    protected $excluder;

    /**
     * Base price for item.
     *
     * @var float
     */
    protected $basePrice;

    /**
     * iIem quantity.
     *
     * @var int
     */
    protected $quantity;

    /**
     * Contains all taxes.
     *
     * @var Collection<Modifier>
     */
    protected $taxes;

    /**
     * Contains all discounts..
     *
     * @var Collection<Modifier>
     */
    protected $discounts;

    /**
     * Calculator constructor.
     *
     * @param float $basePrice
     * @param int $quantity
     * @param Collection $taxes
     * @param Collection $discounts
     */
    public function __construct(
        $basePrice,
        $quantity,
        Collection $taxes = null,
        Collection $discounts = null
    ) {
        $taxes = $taxes ?: collect();

        $this->basePrice = $basePrice;
        $this->quantity = $quantity;
        $this->excluder = new Excluder($taxes);
        $this->taxes = $this->buildTaxes($taxes);
        $this->discounts = $this->buildDiscounts($discounts ?: collect());
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity()
    {
        return (int) $this->quantity;
    }

    /**
     * Returns the unit price.
     *
     * @return float
     */
    public function getCleanBasePrice()
    {
        $base = (float) $this->basePrice;
        return $base - $this->getTaxDiscounter()->getCleanTotalTaxIncluded($base);
    }

    /**
     * Multiply the total by the amount.
     *
     * @return float
     */
    public function getCleanSubtotal()
    {
        return $this->getCleanBasePrice() * $this->getQuantity();
    }

    /**
     * Returns total discounts.
     *
     * @return float
     */
    public function getCleanDiscounts()
    {
        return $this->getDiscounts()->sum(function (Modifier $discount) {
            return $discount->getCleanTotalExtracted();
        });
    }

    /**
     * Returns total tax payable.
     *
     * @return float
     */
    public function getCleanTaxes()
    {
        return $this->getTaxes()->sum(function (Modifier $tax) {
            return $tax->getCleanTotalExtracted();
        });
    }

    /**
     * Returns the definitive total.
     *
     * @return float
     */
    public function getCleanDefinitiveTotal()
    {
        return $this->getCleanSubtotal() - $this->getCleanDiscounts() + $this->getCleanTaxes();
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePrice()
    {
        return Helpers::decimalFormat($this->getCleanBasePrice());
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotal()
    {
        return Helpers::decimalFormat($this->getCleanSubtotal());
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalDiscounts()
    {
        return Helpers::decimalFormat($this->getCleanDiscounts());
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalTaxes()
    {
        return Helpers::decimalFormat($this->getCleanTaxes());
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinitiveTotal()
    {
        return Helpers::decimalFormat($this->getCleanDefinitiveTotal());
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscount($code)
    {
        return $this->getDiscounts()->get($code);
    }

    /**
     * {@inheritdoc}
     */
    public function addDiscount(DiscountContract $discount)
    {
        $this->getDiscounts()->put($discount->getDiscountCode(), $this->makeModifierInstance($discount));
    }

    /**
     * {@inheritdoc}
     */
    public function removeDiscount($code)
    {
        $this->getDiscounts()->forget($code);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            // base
            'base_price' => $this->getBasePrice(),
            'quantity' => $this->getQuantity(),
            'subtotal' => $this->getSubtotal(),

            // total discounts
            'total_discounts' => $this->getTotalDiscounts(),
            'discounts' => $this->getDiscounts()->toArray(),

            // taxes
            'total_taxes' => $this->getTotalTaxes(),
            'taxes' => $this->getTaxes()->toArray(),

            // total
            'definitive_total' => $this->getDefinitiveTotal(),
        ];
    }

    /**
     * Builds simplification of discounts.
     *
     * @param Collection $discounts
     * @return Collection
     */
    protected function buildDiscounts(Collection $discounts)
    {
        $modifier = collect();

        $discounts->each(function (DiscountContract $discount) use ($modifier) {
            $modifier->put($discount->getDiscountCode(), $this->makeModifierInstance($discount));
        });

        return $modifier;
    }

    /**
     * Builds a simplification of taxes.
     *
     * @param Collection $taxes
     * @return Collection
     */
    protected function buildTaxes(Collection $taxes)
    {
        return $taxes->map(function (TaxContract $tax) {
            return $this->makeModifierInstance($tax);
        });
    }

    /**
     * Build a new modifier instance.
     *
     * @param AmountModifierContract $modifier
     * @return Modifier
     */
    protected function makeModifierInstance(AmountModifierContract $modifier)
    {
        return new Modifier($modifier, $this->getCleanSubtotal());
    }

    /**
     * Returns the taxes that are included in the price.
     *
     * @return Excluder
     */
    protected function getTaxDiscounter()
    {
        return $this->excluder;
    }
}
