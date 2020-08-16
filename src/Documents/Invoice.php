<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Modifiers\Taxes\IGV;

class Invoice extends Document
{
    public function getUniqueIdentificationKey(): string
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxes(): array
    {
        return [
            IGV::make(18, $this->includedTax),
        ];
    }
}
