<?php
/**
 * Created on 29/09/17 by enea dhack.
 */

namespace Enea\Cashier;

trait IsJsonable
{
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
