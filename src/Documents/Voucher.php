<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

class Voucher extends Document
{
    public function getUniqueIdentificationKey(): string
    {
        return 'voucher';
    }
}
