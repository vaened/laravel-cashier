<?php
/**
 * Created by enea dhack - 31/05/2017 11:51 AM
 */

namespace Enea\Cashier\Taxes;


use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Contracts\InvoiceContract;

class Invoice implements InvoiceContract
{
    protected const IGV = 18;

    /**
     * @var BusinessOwner
     */
    protected $owner;

    /**
     * Invoice constructor.
     * @param BusinessOwner $owner
     */
    public function __construct( BusinessOwner $owner = null )
    {
        $this->owner = $owner;
    }

    /**
     * Get tax percentage
     *
     * @return int
     */
    public function getTaxPercentageAttribute(): int
    {
        return self::IGV;
    }

    /**
     * Returns the owner of social reason
     *
     * @return BusinessOwner
     * */
    public function getBusinessOwner(): ?BusinessOwner
    {
        return $this->owner;
    }
}