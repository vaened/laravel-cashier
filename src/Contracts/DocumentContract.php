<?php
/**
 * Created by enea dhack - 16/06/17 08:59 PM
 */

namespace Enea\Cashier\Contracts;


/**
 * Interface DocumentContract
 * @package Enea\Cashier\Contracts
 *
 * Specifies a document type - Examples: ballots or invoices
 */
interface DocumentContract
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