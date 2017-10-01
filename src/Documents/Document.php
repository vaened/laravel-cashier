<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Contracts\DocumentContract;
use Illuminate\Support\Collection;

abstract class Document implements DocumentContract
{
    /**
     * @var BusinessOwner
     */
    protected $owner;

    /**
     * Document constructor.
     *
     * @param BusinessOwner $owner
     */
    public function __construct(BusinessOwner $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Returns the owner of social reason.
     *
     * @return null|BusinessOwner
     * */
    public function getBusinessOwner()
    {
        return $this->owner;
    }

    /**
     * Returns the taxes of the document.
     *
     * @return Collection<TaxContract>|null
     */
    public function getTaxes()
    {
        return collect();
    }
}
