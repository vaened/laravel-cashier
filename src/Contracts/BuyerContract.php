<?php
/**
 * Created by enea dhack - 30/05/2017 03:20 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;

interface BuyerContract extends KeyableContract, AttributableContract, Arrayable, Jsonable, JsonSerializable
{
}
