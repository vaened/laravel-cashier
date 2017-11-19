<?php
/**
 * Created by enea dhack - 16/06/17 08:59 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface DocumentContract.
 */
interface DocumentContract extends Arrayable, Jsonable, TaxableContract
{
    /**
     * Returns the key document.
     *
     * @return string|int
     */
    public function getKeyDocument();

    /**
     * Returns the owner of social reason.
     *
     * @return null|BusinessOwner
     * */
    public function getBusinessOwner();
}
