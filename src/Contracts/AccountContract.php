<?php
/**
 * Created by enea dhack - 16/06/17 09:50 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface AccountContract.
 */
interface AccountContract extends AttributableContract
{
    /**
     * Returns identification one in the database - primary key.
     *
     * @return string
     */
    public function getKeyIdentification();

    /**
     * Returns elements that implement 'SalableContract'.
     *
     * @return Collection
     */
    public function getElements();
}
