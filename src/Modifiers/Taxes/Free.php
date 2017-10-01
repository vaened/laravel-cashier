<?php
/**
 * Created on 30/09/17 by enea dhack.
 */

namespace Enea\Cashier\Modifiers\Taxes;

use Enea\Cashier\Modifiers\Taxes\BaseTax as Tax;

class Free extends Tax
{
    /**
     * Free constructor.
     */
    public function __construct()
    {
        parent::__construct(false, 0);
    }

    /**
     * Returns tax description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'free';
    }
}