<?php
/**
 * Created on 29/09/17 by enea dhack.
 */

namespace Enea\Cashier;

trait IsJsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
