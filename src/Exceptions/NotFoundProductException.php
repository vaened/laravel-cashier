<?php
/**
 * Created by enea dhack - 07/08/2020 0:05.
 */

namespace Enea\Cashier\Exceptions;

use InvalidArgumentException;
use Throwable;

class NotFoundProductException extends InvalidArgumentException
{
    public function __construct(string $productID, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Product not found with ID '$productID'", $code, $previous);
    }
}
