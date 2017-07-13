<?php
/**
 * Created by enea dhack - 16/06/17 08:59 PM.
 */

namespace Enea\Cashier\Contracts;

/**
 * Interface DocumentContract.
 */
interface DocumentContract
{
    /**
     * Returns the key document.
     *
     * @return string|int
     */
    public function getKeyDocument();

    /**
     * Get tax percentage.
     *
     * @return int
     */
    public function getTaxPercentageAttribute();

    /**
     * Returns the owner of social reason.
     *
     * @return null|BusinessOwner
     * */
    public function getBusinessOwner();
}
