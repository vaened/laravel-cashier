<?php
/**
 * Created by enea dhack - 17/06/17 03:22 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\DiscountableContract;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Contracts\SalableContract;
use Enea\Cashier\Modifiers\DiscountContract;
use Illuminate\Support\Collection;

abstract class BaseSalableItem extends BaseItem
{
    /**
     * Item document.
     *
     * @var DocumentContract
     * */
    protected $document;

    /**
     * the salable item.
     *
     * @var SalableContract
     */
    protected $salable;

    /**
     * Old quantity.
     *
     * @var int
     * */
    private $old_quantity;

    /**
     * BaseSalableItem constructor.
     *
     * @param SalableContract $salable
     * @param int $quantity
     */
    public function __construct(SalableContract $salable, $quantity)
    {
        parent::__construct($salable, $quantity);
        $this->salable = $salable;
    }

    /**
     * Change quantity for item.
     *
     * @param int $quantity
     * @return void
     */
    public function setQuantity($quantity)
    {
        $this->getCalculator()->setQuantity($quantity);
        $this->old_quantity = $this->old_quantity ?: $quantity;
    }

    /**
     * Returns true in case the quantity of the item has been changed and is no longer the same as in the beginning.
     *
     * @return bool
     */
    public function isTouched()
    {
        return $this->old_quantity != $this->getQuantity();
    }

    /**
     * Adds a discount to the discount collection.
     *
     * @param DiscountContract $discount
     * @return static
     */
    public function addDiscount(DiscountContract $discount)
    {
        if ($this->isDiscountable()) {
            $this->attachDiscount($discount);
        }

        return $this;
    }

    /**
     * Add multiples discounts.
     *
     * @param Collection $discounts
     * @return $this
     */
    public function addDiscounts(Collection $discounts)
    {
        if ($this->isDiscountable()) {
            $discounts->each(function (DiscountContract $discount) {
                $this->attachDiscount($discount);
            });
        }

        return $this;
    }

    /**
     * Remove a discount from the discount collection.
     *
     * @param $code
     * @return static
     */
    public function removeDiscount($code)
    {
        $this->getCalculator()->removeDiscount($code);
        return $this;
    }

    /**
     * Set a document.
     *
     * @param DocumentContract $document
     * @return static
     */
    public function setDocument(DocumentContract $document)
    {
        $this->document = $document;

        if ($this->getSalable()->isTaxable()) {
            $this->getCalculator()->setTaxes($document->getTaxes() ?: collect());
        }

        return $this;
    }

    /**
     * Returns the salable item.
     *
     * @return SalableContract
     */
    public function getSalable()
    {
        return $this->salable;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'is_taxable' => $this->getSalable()->isTaxable(),
            'is_discountable' => $this->isDiscountable(),
        ]);
    }

    /**
     * Attach a discount.
     *
     * @param DiscountContract $discount
     * @return void
     */
    protected function attachDiscount(DiscountContract $discount)
    {
        $this->getCalculator()->addDiscount($discount);
    }
}
