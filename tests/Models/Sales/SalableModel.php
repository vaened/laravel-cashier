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

    protected $fillable = ['id', 'price', 'name'];
    /**
     * Get base price for item
     *
     * @return float
     */
    public function getBasePrice(): float
    {
        return $this->price;
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

    /**
     * Returns item name
     *
     * @return string
     * */
    public function getShortDescription(): ?string
    {
        return $this->name;
    }

    /**
     * Returns the unit of measure of the item
     *
     * @return string
     * */
    public function getMeasure(): ?string
    {
        return null;
    }
}