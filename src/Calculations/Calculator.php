<?php
/**
 * Created by enea dhack - 30/05/2017 02:54 PM.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Contracts\CalculableContract;
use Enea\Cashier\Modifiers\DiscountContract;
use Illuminate\Support\Collection;

/**
 * Class Calculator.
 *
 * @author enea dhack <enea.so@live.com>
 */
class Calculator extends Base
{
    protected $memoryBasePrice;

    protected $memorySubtotal;

    protected $memoryTaxes;

    protected $memoryDiscounts;

    protected $memoryDefinitiveTotal;

    protected $memoryCollectionTaxes;

    protected $memoryCollectionDiscounts;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CalculableContract $calculable,
        $quantity,
        Collection $taxes = null,
        Collection $discounts = null
    ) {
        parent::__construct($calculable, $quantity, $taxes, $discounts);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity($quantity)
    {
        parent::setQuantity($quantity);
        $this->resetMemory();
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxes(Collection $taxes)
    {
        $this->taxes = $taxes;
        $this->resetMemory();
    }

    /**
     * {@inheritdoc}
     */
    public function addDiscount(DiscountContract $discount)
    {
        parent::addDiscount($discount);
        $this->resetMemory();
    }

    /**
     * {@inheritdoc}
     */
    public function removeDiscount($code)
    {
        parent::removeDiscount($code);
        $this->resetMemory();
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxes()
    {
        return $this->memoryCollectionTaxes ?: $this->memoryCollectionTaxes = parent::getTaxes();
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscounts()
    {
        return $this->memoryCollectionDiscounts ?: $this->memoryCollectionDiscounts = parent::getDiscounts();
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanBasePrice()
    {
        return $this->memoryBasePrice ?: $this->memoryBasePrice = parent::getCleanBasePrice();
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanSubtotal()
    {
        return $this->memorySubtotal ?: $this->memorySubtotal = parent::getCleanSubtotal();
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanDiscounts()
    {
        return $this->memoryDiscounts ?: $this->memoryDiscounts = parent::getCleanDiscounts();
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanTaxes()
    {
        return $this->memoryTaxes ?: $this->memoryTaxes = parent::getCleanTaxes();
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanDefinitiveTotal()
    {
        return $this->memoryDefinitiveTotal ?: $this->memoryDefinitiveTotal = parent::getCleanDefinitiveTotal();
    }

    /**
     * Reset all variables.
     *
     * @return void
     */
    protected function resetMemory()
    {
        $this->memoryBasePrice = null;
        $this->memorySubtotal = null;
        $this->memoryTaxes = null;
        $this->memoryDiscounts = null;
        $this->memoryDefinitiveTotal = null;
        $this->memoryCollectionTaxes = null;
        $this->memoryCollectionDiscounts = null;
    }
}
