<?php
/**
 * Created by enea dhack - 17/06/17 03:52 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface ProductContract extends KeyableContract, CalculableContract, TaxableContract, AttributableContract, Arrayable, Jsonable, JsonSerializable
{
    public function getShortDescription(): string;
}
