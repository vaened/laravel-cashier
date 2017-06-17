<?php
/**
 * Created by enea dhack - 16/06/17 10:23 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\AccountElementContract;
use Enea\Cashier\Contracts\SalableContract;
use Illuminate\Database\Eloquent\Model;

class AccountElement extends BaseSalableItem
{

    /**
     * AccountElement constructor.
     * @param AccountElementContract $salable
     * @param int $impostPercentage
     */
    public function __construct(AccountElementContract $salable, int $impostPercentage = 0 )
    {
        parent::__construct($salable, $salable->getQuantity(), $impostPercentage);
    }

}