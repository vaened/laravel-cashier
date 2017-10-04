<?php
/**
 * Created by enea dhack - 16/06/17 08:59 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * Interface DocumentContract.
 */
interface DocumentContract extends Arrayable, Jsonable
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

    /**
     * Returns the taxes of the document.
     *
     * @return Collection<TaxContract>|null
     */
    public function getTaxes();
}
