<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\{KeyableContract, QuoteContract, QuotedProductContract};
use Enea\Cashier\Items\QuotedProduct;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use Illuminate\Support\Collection;

class AccountManager implements Arrayable, Jsonable, KeyableContract
{
    use IsJsonable;

    protected QuoteContract $account;

    protected Collection $elements;

    public function __construct(QuoteContract $account)
    {
        $this->account = $account;
        $this->loadQuotedProductsFrom($account);
    }

    public function getUniqueIdentificationKey(): string
    {
        return $this->getQuote()->getUniqueIdentificationKey();
    }

    public function find($key): ?QuotedProductContract
    {
        return $this->elements->get($key);
    }

    public function hasProduct($key): bool
    {
        return isset($this->elements[$key]);
    }

    public function getQuotedProducts(): Collection
    {
        return $this->elements;
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
        return [
            'id' => $this->getUniqueIdentificationKey(),
            'properties' => $this->getQuote()->getProperties(),
            'products' => $this->getQuotedProducts()->toArray(),
        ];
    }

    private function loadQuotedProductsFrom(QuoteContract $quote): void
    {
        $this->elements = $quote->getQuotedProducts()->map(fn(
            QuotedProductContract $product
        ) => $this->toQuoted($product));
    }

    private function toQuoted(QuotedProductContract $element): QuotedProduct
    {
        return new QuotedProduct($this->account, $element);
    }
}
