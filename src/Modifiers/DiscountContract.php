<?php
/**
 * Created by enea dhack - 29/09/2017 12:00 PM.
 */

namespace Enea\Cashier\Modifiers;

use Enea\Cashier\Contracts\{AttributableContract};
use Illuminate\Contracts\Support\{Arrayable, Jsonable};

interface DiscountContract extends AttributableContract, Arrayable, Jsonable
{
    public function getDiscountCode(): string;

    public function getDescription(): string;

    public function extract(float $total): float;
}
