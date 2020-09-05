<?php
/**
 * Created by enea dhack - 12/08/2020 16:01.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\AttributableContract;
use Enea\Cashier\HasProperties;

class Attributable implements AttributableContract
{
    use HasProperties;

    public function __construct(array $properties)
    {
        $this->setProperties($properties);
    }
}
