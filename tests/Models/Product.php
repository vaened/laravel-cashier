<?php
/**
 * Created by enea dhack - 06/08/2020 17:44.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\ProductContract;
use Enea\Cashier\Modifiers\Tax;
use Enea\Cashier\Taxes;

/**
 * Class Product
 *
 * @package Enea\Tests\Models
 * @author enea dhack <enea.so@live.com>
 *
 * @property int id
 * @property string short_description
 * @property string full_description
 * @property float igv_pct
 * @property float sale_price
 */
class Product extends Model implements ProductContract
{
    protected $fillable = [
        'id',
        'sale_price',
        'short_description',
        'full_description',
        'igv_pct',
    ];

    protected $casts = [
        'sale_price' => 'float',
        'ivg_pct' => 'float',
    ];

    public function getUnitPrice(): float
    {
        return $this->sale_price;
    }

    public function getUniqueIdentificationKey(): string
    {
        return $this->getKey();
    }

    public function getShortDescription(): string
    {
        return $this->short_description;
    }

    public function getTaxes(): array
    {
        return [
            Tax::included(Taxes::IGV, $this->igv_pct),
        ];
    }

    public function getProperties(): array
    {
        return [
            'full_description' => $this->full_description,
        ];
    }
}
