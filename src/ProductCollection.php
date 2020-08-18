<?php
/**
 * Created by enea dhack - 09/08/2020 13:34.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\ProductContract;
use Enea\Cashier\Exceptions\UnrepeatableProductException;
use Enea\Cashier\Items\CartItem;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use Illuminate\Support\Collection;

abstract class ProductCollection implements Arrayable, Jsonable
{
    use IsJsonable;

    private Collection $products;

    public function __construct(Collection $products)
    {
        $this->products = $products;
    }

    abstract public function find(string $productID): ?CartItem;

    public function hasProduct(string $productID): bool
    {
        return $this->products()->offsetExists($productID);
    }

    public function getSubtotal(): float
    {
        return $this->products()->sum(fn(CartItem $item) => $item->getCashier()->getSubtotal());
    }

    public function getTotalTaxes(): float
    {
        return $this->products()->sum(fn(CartItem $item) => $item->getCashier()->getTotalTaxes());
    }

    public function getTotalDiscounts(): float
    {
        return $this->products()->sum(fn(CartItem $item) => $item->getCashier()->getTotalDiscounts());
    }

    public function getTotal(): float
    {
        return $this->products()->sum(fn(CartItem $item) => $item->getCashier()->getTotal());
    }

    public function products(): Collection
    {
        return $this->products;
    }

    public function clear(): void
    {
        $this->products = new Collection();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'products' => $this->products()->toArray(),
            'subtotal' => $this->getSubtotal(),
            'total' => $this->getTotal(),
            'total_taxes' => $this->getTotalTaxes(),
            'total_discounts' => $this->getTotalDiscounts(),
        ];
    }

    protected function addToCollection(CartItem $cartItem): void
    {
        $this->validateUniqueProduct($cartItem->getProduct());
        $this->products->put($cartItem->getUniqueIdentificationKey(), $cartItem);
    }

    private function validateUniqueProduct(ProductContract $product): void
    {
        if ($this->hasProduct($product->getUniqueIdentificationKey())) {
            throw new UnrepeatableProductException($product);
        }
    }
}
