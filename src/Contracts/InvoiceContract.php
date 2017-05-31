<?php
/**
 * Created by enea dhack - 31/05/2017 11:47 AM
 */

namespace Enea\Cashier\Contracts;


interface InvoiceContract
{

    /**
     * Get tax percentage
     *
     * @return int
     */
    public function getTaxPercentageAttribute( ): int;

    /**
     * Returns the owner of social reason
     *
     * @return BusinessOwner
     * */
    public function getBusinessOwner( ): ?BusinessOwner;

}