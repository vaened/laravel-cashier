<?php
/**
 * Created by enea dhack - 29/09/2017 02:13 PM.
 */

namespace Enea\Cashier\Contracts;

interface AttributableContract
{
    /**
     * Returns an array with extra attributes.
     *
     * @return \Illuminate\Support\Collection
     * */
    public function getAdditionalAttributes();
}
