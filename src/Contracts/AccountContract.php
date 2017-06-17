<?php
/**
 * Created by enea dhack - 16/06/17 09:50 PM
 */

namespace Enea\Cashier\Contracts;


use Illuminate\Support\Collection;

/**
 * Interface AccountContract
 * @package Enea\Cashier\Contracts
 *
 * Represents an account payable, must be attached to the shopping cart
 *
 * Implementing this interface will limit the items to choose from by providing a custom list
 * Example case
 * A pre-invoice to be settled, this one has elements that have been loaded since that pre-invoice opened. In this case,
 * it is necessary to validate that the items to be paid are within the detail of said prefacture. Pre-invoice implements 'AccountContract'
 */
interface AccountContract
{

    /**
     * Returns identification one in the database - primary key
     *
     * @return string
     */
    public function getKeyIdentification(): string;

    /**
     * Returns an array with extra properties
     *
     * @return array
     * */
    public function getCustomProperties( ): array;

    /**
     * Returns elements that implement 'SalableContract'
     *
     * @return Collection
     */
    public function getElements( ): Collection;

}