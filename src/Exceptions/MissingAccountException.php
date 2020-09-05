<?php
/**
 * Created by enea dhack - 21/07/2020 23:08.
 */

namespace Enea\Cashier\Exceptions;

use BadMethodCallException;
use Throwable;

class MissingAccountException extends BadMethodCallException
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct("No account available", $code, $previous);
    }
}
