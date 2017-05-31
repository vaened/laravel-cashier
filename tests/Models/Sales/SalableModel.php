<?php
/**
 * Created by enea dhack - 30/05/2017 06:08 PM
 */

namespace Enea\Tests\Models\Sales;


use Enea\Cashier\Contracts\SalableContract;
use Enea\Tests\CalculatorTest;
use Illuminate\Database\Eloquent\Model;

class SalableModel extends Model implements SalableContract
{

    protected $fillable = ['id'];
    /**
     * Get base price for item
     *
     * @return float
     */
    public function getBasePriceAttribute(): float
    {
        return CalculatorTest::PRICE;
    }

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return int|string
     */
    public function getItemKey( )
    {
        return $this->getKey( );
    }

}