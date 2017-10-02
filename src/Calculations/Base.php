<?php
/**
 * Created by enea dhack - 02/10/2017 02:49 PM.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Helpers;
use Enea\Cashier\IsJsonable;
use Enea\Cashier\Modifiers\AmountModifierContract;
use Enea\Cashier\Modifiers\DiscountContract;
use Enea\Cashier\Modifiers\TaxContract;
use Illuminate\Support\Collection;

class Base
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
        $discounts = $discounts ?: collect();

        $this->setQuantity($quantity);
        $this->basePrice = $basePrice;
        $this->taxes = $taxes;
        $this->prepareDiscounts($discounts);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxes(Collection $taxes)
    {
        $this->taxes = $taxes;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxes()
    {
        return $this->buildTaxes($this->taxes);
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscounts()
    {
        return $this->buildDiscounts($this->discounts);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity()
    {
        return (int) $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanBasePrice()
    {
        $base = (float) $this->basePrice;
        return $base - $this->getTaxDiscounter()->getCleanTotalTaxIncluded($base);
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanSubtotal()
    {
        return $this->getCleanBasePrice() * $this->getQuantity();
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanDiscounts()
    {
        return $this->getDiscounts()->sum(function (Modifier $discount) {
            return $discount->getCleanTotalExtracted();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanTaxes()
    {
        return $this->getTaxes()->sum(function (Modifier $tax) {
            return $tax->getCleanTotalExtracted();
        });
    }

    /**
     * {@inheritdoc}
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
        $this->discounts->put($discount->getDiscountCode(), $discount);
    }

    /**
     * {@inheritdoc}
     */
    public function removeDiscount($code)
    {
        $this->discounts->forget($code);
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
     * Prepare all discounts.
     *
     * @param Collection $discounts
     * @return void
     */
    protected function prepareDiscounts(Collection $discounts)
    {
        $this->discounts = collect();
        $discounts->each(function (DiscountContract $discount) {
            $this->addDiscount($discount);
        });
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
        return new Excluder($this->taxes);
    }
}
