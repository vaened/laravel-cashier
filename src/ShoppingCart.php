<?php
/**
 * Created by enea dhack - 30/05/2017 03:31 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\{BuyerContract, DocumentContract, ProductContract, QuoteContract};
use Enea\Cashier\Exceptions\{MissingAccountException, NotFoundProductException};
use Enea\Cashier\Items\{ProductCartItem, QuotedProductCartItem};
use Enea\Cashier\Modifiers\DiscountContract;

class ShoppingCart extends Manager
{
    protected array $discounts = [];

    protected ?QuoteManager $quoteManager = null;

    private array $taxes;

    public function __construct(BuyerContract $buyer, DocumentContract $document, array $taxes = [])
    {
        parent::__construct($buyer, $document);
        $this->taxes = $taxes;
    }

    public function push(ProductContract $product, int $quantity = 1): ProductCartItem
    {
        $cartItem = $this->createCartItem($product, $quantity);
        $this->addProduct($cartItem);
        return $cartItem;
    }

    public function pull(string $productID): ProductCartItem
    {
        $this->validateQuotePull($productID);
        $cartItem = $this->getQuoteManager()->find($productID)->toSell();
        $this->addProduct($cartItem);
        return $cartItem;
    }

    public function pullAll(): self
    {
        $this->validateQuote();

        $products = $this->products()->keys()->toArray();
        
        $this->getQuoteManager()->products()->filter(fn(
            QuotedProductCartItem $quoted
        ): bool => ! in_array($quoted->getUniqueIdentificationKey(), $products))->each(fn(
            QuotedProductCartItem $quoted
        ) => $this->pull($quoted->getUniqueIdentificationKey()));

        return $this;
    }

    public function find(string $productID): ?ProductCartItem
    {
        return $this->products()->get($productID);
    }

    public function remove(string $productID): void
    {
        $this->products()->forget($productID);
    }

    public function setDocument(DocumentContract $document): void
    {
        $this->document = $document;
        $this->products()->each(fn(ProductCartItem $product) => $product->applyTaxes($document->taxesToUse()));
    }

    public function addDiscount(DiscountContract $discount): void
    {
        $this->discounts[$discount->getDiscountCode()] = $discount;
        $this->products()->each(fn(ProductCartItem $product) => $product->addDiscounts([$discount]));
    }

    public function removeDiscount(string $code): void
    {
        unset($this->discounts[$code]);
        $this->products()->each(fn(ProductCartItem $product) => $product->removeDiscount($code));
    }

    public function attach(QuoteContract $quote): self
    {
        $this->quoteManager = new QuoteManager($quote);
        $this->clear();
        return $this;
    }

    public function detach(): void
    {
        $this->quoteManager = null;
        $this->clear();
    }

    public function getQuoteManager(): ?QuoteManager
    {
        return $this->quoteManager;
    }

    public function hasQuote(): bool
    {
        return $this->quoteManager !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'discounts' => Helpers::convertToArray($this->discounts),
            'document' => $this->getDocument()->toArray(),
            'quote' => $this->hasQuote() ? $this->getQuoteManager()->toArray() : null,
        ]);
    }

    private function validateQuotePull(string $productID): void
    {
        $this->validateQuote();

        if (! $this->getQuoteManager()->hasProduct($productID)) {
            throw new NotFoundProductException($productID);
        }
    }

    private function validateQuote(): void
    {
        if (! $this->hasQuote()) {
            throw new MissingAccountException();
        }
    }

    private function addProduct(ProductCartItem $cartItem): void
    {
        $cartItem->addDiscounts($this->discounts);
        $cartItem->applyTaxes($this->document->taxesToUse());
        $this->addToCollection($cartItem);
    }

    protected function createCartItem(ProductContract $product, int $quantity): ProductCartItem
    {
        return new ProductCartItem($product, $quantity, $this->taxes);
    }
}
