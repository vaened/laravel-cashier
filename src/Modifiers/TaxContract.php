<?php
/**
 * Created on 29/09/17 by enea dhack.
 */

namespace Enea\Cashier\Modifiers;

interface TaxContract extends AmountModifierContract
{
    /**
     * Returns tax description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Returns true in case the tax is already included in the price.
     *
     * @return bool
     */
    public function isIncluded();
}
