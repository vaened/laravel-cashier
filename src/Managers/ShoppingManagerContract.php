<?php
/**
 * Created by enea dhack - 17/08/2020 21:06.
 */

namespace Enea\Cashier\Managers;

use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\ShoppingCart;

interface ShoppingManagerContract
{
    public function initialize(BuyerContract $buyer, DocumentContract $document = null, array $taxes = []): ShoppingCart;

    public function find(string $token): ?ShoppingCart;

    public function drop(string $token): void;

    public function has(string $token): bool;

    public function flush(): void;
}