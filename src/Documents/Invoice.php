<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

use Enea\Cashier\Taxes;

class Invoice extends Document
{
    public static function create(array $taxes = [Taxes::IGV]): self
    {
        return new static($taxes);
    }

    public function getUniqueIdentificationKey(): string
    {
        return 'invoice';
    }
}
