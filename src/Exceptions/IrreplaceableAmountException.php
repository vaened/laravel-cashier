<?php
/**
 * Created by enea dhack - 30/05/2017 03:45 PM
 */

namespace Enea\Cashier\Exceptions;

use InvalidArgumentException;
use Throwable;

class IrreplaceableAmountException extends InvalidArgumentException
{

    public function __construct($quantity, $code = 0, Throwable $previous = null)
    {
        parent::__construct("It is not possible to manipulate the item quantity to '{$quantity}'", $code, $previous);
    }
}