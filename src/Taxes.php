<?php
/**
 * Created by enea dhack - 30/07/2020 22:45.
 */

namespace Enea\Cashier;

use Vaened\Enum\Enum;

/**
 * Class Taxes
 *
 * @package Enea\Core
 * @author enea dhack <enea.so@live.com>
 *
 * @method static Taxes IGV()
 * @method static Taxes IVA()
 */
class Taxes extends Enum
{
    const IGV = 'IGV';

    const IVA = 'IVA';

    public function __toString(): string
    {
        return $this->value();
    }
}
