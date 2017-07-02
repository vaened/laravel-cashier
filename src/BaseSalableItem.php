<?php
/**
 * Created by enea dhack - 17/06/17 03:22 PM
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\SalableContract;

abstract class BaseSalableItem extends BaseItem
{

    /**
     * BaseSalableItem constructor.
     *
     * @param SalableContract $salable
     * @param int $quantity
     * @param int $impostPercentage
     */
    public function __construct(SalableContract $salable, $quantity, $impostPercentage = 0 )
    {
        parent::__construct( $salable );
        parent::setQuantity($quantity);
        $this->setImpostPercentage($impostPercentage);
    }

    /**
     * Return main model
     *
     * @return SalableContract
     */
    public function getSalable()
    {
        return $this->element;
    }

    /**
     * Set a tax rate for the item
     *
     * @param int $percentage
     */
    public function setImpostPercentage( $percentage)
    {
        if ($this->getSalable( )->isTaxable()) {
            $this->recalculate = true;
            $this->impostPercentage = $percentage;
        }
    }

}