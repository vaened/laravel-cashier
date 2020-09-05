<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\{KeyableContract, QuoteContract, QuotedProductContract};
use Enea\Cashier\Items\QuotedProductCartItem;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use Illuminate\Support\Collection;

class QuoteManager extends ProductCollection implements Arrayable, Jsonable, KeyableContract
{
    use IsJsonable;

    protected QuoteContract $account;

    public function __construct(QuoteContract $account)
    {
        parent::__construct(new Collection());
        $this->loadQuotedProductsFrom($account);
        $this->account = $account;
    }

    public function getUniqueIdentificationKey(): string
    {
        return $this->getQuote()->getUniqueIdentificationKey();
    }

    public function find(string $productID): ?QuotedProductCartItem
    {
        return $this->products()->get($productID);
    }

    public function getQuote(): QuoteContract
    {
        return $this->account;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge([
            'id' => $this->getUniqueIdentificationKey(),
            'properties' => $this->getQuote()->getProperties(),
        ], parent::toArray());
    }

    private function loadQuotedProductsFrom(QuoteContract $quote): void
    {
        $quote->getQuotedProducts()->map(fn(
            QuotedProductContract $quoted
        ) => $this->addToCollection(new QuotedProductCartItem($quoted)));
    }
}
