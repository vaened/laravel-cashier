<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Modifiers\Taxes\IGV;

class Invoice extends Document
{
    /**
     * Invoice constructor.
     *
     * @param BusinessOwner $owner
     */
    public function __construct(BusinessOwner $owner = null)
    {
        parent::__construct($owner);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeyDocument()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxes()
    {
        return collect([
            IGV::make(18),
        ]);
    }
}
