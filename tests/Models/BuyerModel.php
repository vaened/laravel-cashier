<?php
/**
 * Created by enea dhack - 30/05/2017 06:12 PM
 */

namespace Enea\Tests\Models;


use Enea\Cashier\Contracts\BuyerContract;
use Illuminate\Database\Eloquent\Model;

class BuyerModel extends Model implements BuyerContract
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

}