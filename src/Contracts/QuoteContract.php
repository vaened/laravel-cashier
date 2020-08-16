<?php
/**
 * Created by enea dhack - 16/06/17 09:50 PM.
 */

namespace Enea\Cashier\Contracts;

use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use Illuminate\Support\Collection;
use JsonSerializable;

interface QuoteContract extends KeyableContract, AttributableContract, Arrayable, Jsonable, JsonSerializable
{
    public function getBuyer(): BuyerContract;

    public function getQuotedProducts(): Collection;
}
