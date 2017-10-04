<?php
/**
 * Created by enea dhack - 17/06/17 01:09 PM.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\AccountElementContract;
use Illuminate\Database\Eloquent\Model;

class PreinvoiceItem extends Model implements AccountElementContract
{
    protected $fillable = ['quantity', 'id', 'description', 'measure', 'price', 'taxable'];

    /**
     * Get item quantity.
     *
     * @return int
     * */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Key that identifies the article as unique.
     *
     * @return int|string
     */
    public function getItemKey()
    {
        return $this->getKey();
    }

    /**
     * Returns item name.
     *
     * @return string
     * */
    public function getShortDescription()
    {
        // $this->product->description;
        return $this->description;
    }

    /**
     * Returns the unit of measure of the item.
     *
     * @return string
     * */
    public function getMeasure()
    {
        // $this->product->measure->name;
        return $this->measure;
    }

    /**
     * Get base price for item.
     *
     * @return float
     */
    public function getBasePrice()
    {
        return $this->price;
    }

    /**
     * Returns true in case of being subject to tax.
     *
     * @return bool
     */
    public function isTaxable()
    {
        return $this->taxable;
    }

    /**
     * Returns an array with extra attributes.
     *
     * @return \Illuminate\Support\Collection
     * */
    public function getAdditionalAttributes()
    {
        return collect();
    }
}
