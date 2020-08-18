<?php
/**
 * Created by enea dhack - 12/06/17 10:17 PM.
 */

namespace Enea\Cashier\Managers;

use Enea\Cashier\Contracts\{BuyerContract, DocumentContract};
use Enea\Cashier\Documents\Free;
use Enea\Cashier\ShoppingCart;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;

class SessionShoppingManager implements ShoppingManagerContract
{
    protected Store $session;

    protected string $key;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function initialize(BuyerContract $buyer, DocumentContract $document = null, array $taxes = []): ShoppingCart
    {
        $shopping = new ShoppingCart($buyer, $document ?: Free::create(), $taxes);

        if (! $this->isInitiated()) {
            $this->session->put($this->key(), new Collection());
        }

        $this->attach($shopping);

        return $shopping;
    }

    public function find(string $token): ?ShoppingCart
    {
        return $this->carts()->get($token);
    }

    public function drop(string $token): void
    {
        $this->carts()->forget($token);
    }

    public function flush(): void
    {
        $this->session->put($this->key(), new Collection());
    }

    protected function isInitiated(): bool
    {
        return $this->session->has($this->key());
    }

    protected function attach(ShoppingCart $shopping): void
    {
        $this->carts()->put($shopping->getGeneratedToken(), $shopping);
    }

    protected function key(): string
    {
        return $this->key ??= config('cashier.session_key', 'default_laravel_shopping_session_key');
    }

    protected function carts(): Collection
    {
        return $this->session->get($this->key(), new Collection());
    }
}
