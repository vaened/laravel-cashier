<?php
/**
 * Created by enea dhack - 31/05/2017 11:35 AM.
 */

namespace Enea\Cashier\Contracts;

/**
 * Turns the model into a business name for use on an invoice.
 */
interface BusinessOwner
{
    /**
     * Identification of the owner of the business name.
     *
     * @return int|string
     * */
    public function getBusinessOwnerKey();

    /**
     * Returns the taxpayer's unique identification.
     *
     * @return string
     */
    public function getTaxpayerIdentification();

    /**
     * Returns the social reason.
     *
     * @return string
     */
    public function getDescription();
}
