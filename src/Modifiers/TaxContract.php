<?php
/**
 * Created on 29/09/17 by enea dhack.
 */

namespace Enea\Cashier\Modifiers;

use Illuminate\Contracts\Support\{Arrayable, Jsonable};

interface TaxContract extends Arrayable, Jsonable
{
    public function isIncluded(): bool;

    public function getPercentage(): float;

    public function getName(): string;
}
