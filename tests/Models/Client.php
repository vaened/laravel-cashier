<?php
/**
 * Created by enea dhack - 06/08/2020 18:18.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\BuyerContract;

/**
 * Class Client
 *
 * @package Enea\Tests\Models
 * @author enea dhack <enea.so@live.com>
 *
 * @property int id
 * @property string full_name
 */
class Client extends Model implements BuyerContract
{
    public function getUniqueIdentificationKey(): string
    {
        return $this->getKey();
    }

    public function getProperties(): array
    {
        return [
            'full_name' => $this->full_name,
        ];
    }
}
