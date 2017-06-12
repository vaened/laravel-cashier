<?php
/**
 * Created by enea dhack - 12/06/2017 02:12 PM
 */

namespace Enea\Tests\Models\Sales;


use Enea\Cashier\Contracts\CountableStaticContract;
use Enea\Cashier\Contracts\SalableContract;
use Illuminate\Database\Eloquent\Model;

class SaleStaticQuantity extends Model implements SalableContract, CountableStaticContract
{
    protected $fillable = ['id', 'price'];
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
     * Get item quantity
     *
     * @return int
     * */
    public function getQuantity(): int
    {
        return 10;
    }

    /**
     * Returns item name
     *
     * @return string
     * */
    public function getFullName(): ?string
    {
        return null;
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