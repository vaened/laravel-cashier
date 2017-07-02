<?php
/**
 * Created by enea dhack - 17/06/17 03:01 PM
 */

namespace Enea\Tests\Models;


use Enea\Cashier\Contracts\DiscountableContract;

class DiscountableProduct extends Product implements DiscountableContract
{
    protected $fillable = [ 'id', 'price', 'description', 'taxable', 'discount' ];
    public $incrementing = false;

    /**
     * Get the item discount in percentage
     *
     * @return int
     */
    public function getDiscountPercentage()
    {
        return $this->discount;
    }
}