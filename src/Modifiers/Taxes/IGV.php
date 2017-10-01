<?php
/**
 * Created on 30/09/17 by enea dhack.
 */

namespace Enea\Cashier\Modifiers\Taxes;

use Enea\Cashier\Modifiers\Taxes\BaseTax as Tax;

class IGV extends Tax
{
    /**
     * IGV constructor.
     *
     * @param int $percentage
     * @param bool $included
     */
    public function __construct($percentage = 18, $included = false)
    {
        parent::__construct($percentage, $included);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'general sales tax';
    }
}
