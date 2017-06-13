<?php
/**
 * Created by enea dhack - 12/06/2017 02:13 PM
 */

namespace Enea\Tests\Models;


use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DetailedStaticContract;
use Enea\Tests\Models\Sales\SaleStaticQuantity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PreinvoiceModel extends Model implements BuyerContract, DetailedStaticContract
{
    protected $fillable = ['id', 'full_name'];

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return int|string
     */
    public function getBuyerKey( )
    {
        return $this->getKey();
    }

    /**
     * Returns default model detail for purchase
     *
     * @return Collection
     */
    public function getElements(): Collection
    {
        return new Collection([
            new SaleStaticQuantity(['price' => 100, 'id' => 5]),
            new SaleStaticQuantity(['price' => 200, 'id' => 6]),
        ]);
    }
}