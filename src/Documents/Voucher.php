<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

class Voucher extends Document
{
    /**
     * Voucher constructor.
     */
    public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * Returns the key document.
     *
     * @return string|int
     */
    public function getKeyDocument()
    {
        return 'voucher';
    }
}
