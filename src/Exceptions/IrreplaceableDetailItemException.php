<?php
/**
 * Created by enea dhack - 13/06/2017 09:47 PM
 */

namespace Enea\Cashier\Exceptions;


use Enea\Cashier\Contracts\DetailedStaticContract;
use RuntimeException;
use Throwable;

class IrreplaceableDetailItemException extends RuntimeException
{
    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        $contract = DetailedStaticContract::class;
        parent::__construct("It is not possible to modify the detail of a class that implements '{$contract}'", $code, $previous);
    }
}