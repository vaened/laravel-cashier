<?php
/**
 * Created by enea dhack - 17/06/17 01:59 PM.
 */

namespace Enea\Tests\Documents;

use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Contracts\DocumentContract;

class Invoice implements DocumentContract
{
    /**
     * @var BusinessOwner
     */
    private $owner;

    const IGV = 18;

    /**
     * Invoice constructor.
     *
     * @param BusinessOwner $owner
     */
    public function __construct(BusinessOwner $owner = null)
    {
        $this->owner = $owner;
    }

    /**
     * Get tax percentage.
     *
     * @return int
     */
    public function getTaxPercentageAttribute()
    {
        return self::IGV;
    }

    /**
     * Returns the owner of social reason.
     *
     * @return BusinessOwner
     * */
    public function getBusinessOwner()
    {
        return $this->owner;
    }

    /**
     * Returns the key document.
     *
     * @return string|int
     */
    public function getKeyDocument()
    {
        return 1;
    }
}
