<?php
/**
 * Created by enea dhack - 16/06/17 09:50 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface AccountContract.
 */
interface AccountContract
{
    /**
     * Returns identification one in the database - primary key.
     *
     * @return string
     */
    public function getKeyIdentification();

    /**
     * Returns an array with extra properties.
     *
     * @return array
     * */
    public function getCustomProperties();

    /**
     * Returns elements that implement 'SalableContract'.
     *
     * @return Collection
     */
    public function getElements();
}
