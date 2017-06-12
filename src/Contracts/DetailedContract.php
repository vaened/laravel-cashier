<?php
/**
 * Created by enea dhack - 12/06/2017 11:32 AM
 */

namespace Enea\Cashier\Contracts;


use Illuminate\Support\Collection;

interface DetailedContract
{

    /**
     * Returns default model detail for purchase
     *
     * @return Collection
     */
    public function getElements( ): Collection;

}