<?php
/**
 * Created by enea dhack - 17/06/17 01:04 PM.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\BuyerContract;
use Illuminate\Database\Eloquent\Model;

class Client extends Model implements BuyerContract
{
    protected $fillable = ['id', 'name', 'address'];

    public function getBuyerKey()
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     * */
    public function getAdditionalAttributes()
    {
        return collect();
    }
}
