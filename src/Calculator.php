<?php
/**
 * Created by enea dhack - 30/05/2017 02:54 PM
 */

namespace Enea\Cashier;


use Illuminate\Contracts\Support\Arrayable;

class Calculator implements Arrayable
{

    /**
     * Default value
     *
     * @var int
     * */
    protected const ZERO = 0;

    /**
     * Absolute value
     *
     * @var int
     * */
    protected const ABSOLUTE = 100;

    /**
     * Base price for item
     *
     * @var float
     */
    private $basePrice;

    /**
     * iIem quantity
     *
     * @var int
     */
    private $quantity;

    /**
     * Tax price percentage
     *
     * @var int
     */
    private $impostPercentage;

    /**
     * Discount item percentage
     *
     * @var int
     */
    private $discountPercentage;

    /**
     * Plan discount Percentage
     *
     * @var int
     */
    private $planDiscountPercentage;


    /**
     * Calculator constructor.
     *
     * @param float $basePrice
     * @param int $quantity
     * @param int $impostPercentage
     * @param int $discountPercentage
     * @param int $planDiscountPercentage
     */
    public function __construct(
        float $basePrice,
        int $quantity,
        int $impostPercentage = self::ZERO,
        int $discountPercentage = self::ZERO,
        int $planDiscountPercentage = self::ZERO
    ) {
        $this->basePrice = $basePrice;
        $this->quantity = $quantity;
        $this->impostPercentage = $impostPercentage;
        $this->discountPercentage = $discountPercentage;
        $this->planDiscountPercentage = $planDiscountPercentage;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            // base
            'base_price' => $this->basePrice,
            'quantity' => $this->getQuantity( ),
            'subtotal' => $this->getSubtotal( ),

            // discounts
            'plan_discount' => $this->getPlanDiscount( ),
            'discount' => $this->getDiscount( ),
            'total_discounts' => $this->getTotalDiscounts( ),

            // taxes
            'general_sale_tax' => $this->getImpost( ),
            'tax_percentage' => $this->getImpostPercentage( ),

            // total
            'definitive_total' => $this->getDefinitiveTotal( ),
        );
    }

    /**
     * Returns the requested quantity for the item
     *
     * @return int
     */
    public function getQuantity( ): int
    {
        return $this->quantity;
    }

    /**
     * Returns the assigned tax
     *
     * @return float
     */
    public function getImpostPercentage( ): float
    {
        return $this->toPercentage($this->impostPercentage);
    }

    /**
     * Returns the assigned discount item
     *
     * @return float
     */
    public function getDiscountPercentage(): float
    {
        return $this->toPercentage($this->discountPercentage);
    }

    /**
     * Returns the assigned plan discount item
     *
     * @return float
     */
    public function getPlanDiscountPercentage(): float
    {
        return $this->toPercentage($this->planDiscountPercentage);
    }

    /**
     * Returns the assigned discount item
     *
     * @param int $percentage
     * @return self
     */
    public function setDiscountPercentage( int $percentage): self
    {
        $this->discountPercentage = $percentage;
        return $this;
    }

    /**
     * Multiply the total by the amount
     *
     * @return float
     */
    public function getSubtotal( ): float
    {
        return $this->format($this->basePrice * $this->getQuantity( ));
    }

    /**
     * Returns discount item
     *
     * @return float
     */
    public function getDiscount( ): float
    {
        return $this->format($this->getSubtotal( ) * $this->getDiscountPercentage());
    }


    /**
     * Returns plan discount
     *
     * @return float
     */
    public function getPlanDiscount( ): float
    {
        return $this->format($this->getSubtotal( ) * $this->getPlanDiscountPercentage( ));
    }

    /**
     * Total sum of discounts
     *
     * @return float
     */
    public function getTotalDiscounts( ): float
    {
        return $this->format($this->getDiscount( ) + $this->getPlanDiscount( ));
    }

    /**
     * Get general sale sax
     *
     * @return float
     */
    public function getImpost( ): float
    {
        return $this->format($this->getSubtotal( ) * $this->getImpostPercentage( ));
    }

    /**
     * Returns total definitive
     *
     * @return float
     */
    public function getDefinitiveTotal( ): float
    {
        return $this->format($this->getSubtotal( ) - $this->getTotalDiscounts( ) + $this->getImpost( ));
    }

    /**
     * Converts to decimals
     *
     * @param int $percentage
     * @return float
     */
    protected function toPercentage( int $percentage ): float
    {
        return $percentage / self::ABSOLUTE;
    }

    /**
     * format decimal
     *
     * @param float $total
     * @return float
     */
    protected function format(float $total ): float
    {
        $decimal = $decimal ?? $decimal = config('cashier.decimals', 3);

        return round($total, $decimal);
    }

}