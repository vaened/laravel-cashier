<?php
/**
 * Created by enea dhack - 21/07/2020 22:36.
 */

namespace Enea\Cashier\Exceptions;

use Enea\Cashier\Contracts\ProductContract;
use InvalidArgumentException;
use Throwable;

class UnrepeatableProductException extends InvalidArgumentException
{
    public function __construct(ProductContract $product, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("The product '{$product->getShortDescription()}' cannot be added more than once", $code, $previous);
    }
}
