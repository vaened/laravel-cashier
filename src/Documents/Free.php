<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

class Free extends Document
{
    public function getUniqueIdentificationKey(): string
    {
        return 'free';
    }
}
