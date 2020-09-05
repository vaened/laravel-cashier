<?php
/**
 * Created by enea dhack - 16/06/17 08:59 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;

interface DocumentContract extends Arrayable, Jsonable, KeyableContract, JsonSerializable
{
    public function taxesToUse(): array;
}
