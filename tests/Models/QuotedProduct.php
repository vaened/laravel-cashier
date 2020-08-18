<?php
/**
 * Created by enea dhack - 06/08/2020 18:26.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\QuotedProductContract;
use Enea\Cashier\Modifiers\Discount;
use Enea\Cashier\Modifiers\Tax;
use Enea\Cashier\Taxes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class QuotedProductCartItem
 *
 * @package Enea\Tests\Models
 * @author enea dhack <enea.so@live.com>
 *
 * @property int quote_id
 * @property int product_id
 * @property int number
 * @property int quantity
 * @property float sale_price
 * @property float discount_pct
 * @property float taxes_pct
 * @property Product product
 */
class QuotedProduct extends Model implements QuotedProductContract
{
    protected $primaryKey = 'quote_id';

    protected $with = ['product'];

    protected $fillable = [
        'quote_id',
        'product_id',
        'quantity',
        'sale_price',
        'discount_pct',
        'taxes_pct',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUniqueIdentificationKey(): string
    {
        return $this->product_id;
    }

    public function getShortDescription(): string
    {
        return $this->product->getShortDescription();
    }

    public function getUnitPrice(): float
    {
        return $this->sale_price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getDiscounts(): array
    {
        return [
            Discount::percentage($this->discount_pct)->setCode('GENERIC'),
        ];
    }

    public function getTaxes(): array
    {
        return [
            Tax::included(Taxes::IGV, $this->taxes_pct),
        ];
    }

    public function getProperties(): array
    {
        return [
            'quote_id' => $this->quote_id
        ];
    }
}
