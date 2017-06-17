<?php
/**
 * Created by enea dhack - 17/06/17 01:06 PM
 */

namespace Enea\Tests\Models;


use Enea\Cashier\Contracts\SalableContract;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements SalableContract
{
    protected $fillable = ['id', 'price', 'description', 'taxable', 'custom_property'];
    public $incrementing = false;

    protected $casts = [
        'taxable' => 'boolean'
    ];
    /**
     * Key that identifies the article as unique
     *
     * @return int|string
     */
    public function getItemKey()
    {
        return $this->getKey();
    }

    /**
     * Returns item name
     *
     * @return string
     * */
    public function getShortDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Returns the unit of measure of the item
     *
     * @return string
     * */
    public function getMeasure(): ?string
    {
        // $this->measure->name;
        return $this->measure;
    }

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
     * Returns an array with extra properties
     *
     * @return array
     * */
    public function getCustomProperties(): array
    {
        return [
            'custom_property' => $this->custom_property,
        ];
    }

    /**
     * Returns true in case of being subject to tax
     *
     * @return bool
     */
    public function isTaxable(): bool
    {
        return $this->taxable;
    }
}