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
     */
    public function __construct(AccountElementContract $salable)
    {
        parent::__construct($salable, $salable->getQuantity());
    }
}
