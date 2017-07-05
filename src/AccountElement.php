<?php
/**
 * Created by enea dhack - 16/06/17 10:23 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\AccountElementContract;

class AccountElement extends BaseSalableItem
{
    /**
     * AccountElement constructor.
     *
     * @param AccountElementContract $salable
     * @param int $impostPercentage
     */
    public function __construct(AccountElementContract $salable, $impostPercentage = 0)
    {
        parent::__construct($salable, $salable->getQuantity(), $impostPercentage);
    }
}
