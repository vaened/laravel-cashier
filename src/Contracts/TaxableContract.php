<?php
/**
 * Created by enea dhack - 04/10/2017.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Support\Collection;

interface TaxableContract
{
    /**
     * Returns the taxes of the document.
     *
     * @return Collection<TaxContract>|null
     */
    public function getTaxes();
}
