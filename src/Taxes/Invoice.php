<?php
/**
 * Created by enea dhack - 31/05/2017 11:51 AM.
 */

namespace Enea\Cashier\Taxes;

use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Contracts\DocumentContract;

/**
 * Class Invoice.
 *
 * @author enea dhack <enea.so@live.com>
 *
 * Class representing the general income tax
 */
class Invoice implements DocumentContract
{
    const IGV = 18;

    /**
     * @var BusinessOwner
     */
    protected $owner;

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
     * @return null|BusinessOwner
     * */
    public function getBusinessOwner()
    {
        return $this->owner;
    }
}
