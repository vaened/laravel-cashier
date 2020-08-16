<?php
/**
 * Created by enea dhack - 30/05/2017 03:31 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\AccountContract;
use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Contracts\ProductContract;
use Enea\Cashier\Documents\Free as FreeDocument;
use Enea\Cashier\Exceptions\IrreplaceableDetailItemException;
use Enea\Cashier\Exceptions\OneAccountAtTimeException;
use Enea\Cashier\Modifiers\DiscountContract;

class ShoppingCart extends BaseManager
{
    protected ?AccountManager $accountManager;

    protected DocumentContract $document;

    protected array $discounts;

    public function __construct(BuyerContract $buyer, DocumentContract $document = null, array $discounts = [])
    {
        parent::__construct($buyer);
        $this->discounts = $discounts ?: [];
        $this->setDocument($document ?: FreeDocument::create());
    }

    /**
     * Attaches an account to pay and limits the elements to the detail of said account.
     *
     * @param AccountContract $account
     * @return ShoppingCart
     * @throws OneAccountAtTimeException
     */
    public function attach(AccountContract $account)
    {
        if ($this->isAttachedAccount()) {
            throw new OneAccountAtTimeException();
        }
        $this->clean();
        $this->accountManager = new AccountManager($account, $this->document, $this->discounts);
        return $this;
    }

    public function detach(): self
    {
        if ($this->isAttachedAccount()) {
            $this->accountManager = null;
            $this->clean();
        }

        return $this;
    }

    /**
     * Add a new item to the collection and return true if successful, if the buyer
     * has implemented the 'DetailedStaticContract' interface,
     * you will not be able to use this method.
     *
     * @param ProductContract $salable
     * @param int $quantity
     * @return bool
     */
    public function push(ProductContract $salable, $quantity = 1)
    {
        if ($this->isAttachedAccount()) {
            throw new IrreplaceableDetailItemException();
        }

        if ($has = ! $this->has($salable->getUniqueIdentificationKey())) {
            $this->add($this->makeSalableItem($salable, $quantity));
        }

        return $has;
    }

    /**
     * Passes an item from the store to the collection and returns true on success.
     *
     * @param string $key
     * @return bool
     */
    public function pull($key)
    {
        if ($has = $this->getAccountManager()->has($key)) {
            $element = $this->getAccountManager()->find($key);
            $this->add($this->makeSalableItem($element->getSalable(), $element->getQuantity()));
        }

        return $has;
    }

    /**
     * Move all elements from storage to collection for purchase.
     *
     * @return ShoppingCart
     */
    public function pullAll()
    {
        $this->getAccountManager()->getElements()->each(function (CartAccountProduct $element) {
            $this->pull($element->getElementKey());
        });
    }

    /**
     * Returns a item by identification.
     *
     * @param string|int $key
     * @return SalableItem|null
     */
    public function find($key)
    {
        return $this->collection()->get($key);
    }

    /**
     * Removes an item from the collection.
     *
     * @param string|int $key
     * @return bool
     */
    public function remove($key)
    {
        if ($has = $this->has($key)) {
            $this->collection()->forget($key);
        }

        return $has;
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->collection()[$key]);
    }

    /**
     * Returns the attached account.
     *
     * @return AccountManager
     */
    public function getAccountManager()
    {
        return $this->accountManager;
    }

    /**
     * Set the payment document and extract tex percentage.
     *
     * @param DocumentContract $document
     * @return void
     */
    public function setDocument(DocumentContract $document)
    {
        $this->document = $document;
        $setDocument = function (CartProduct $item) use ($document) {
            $item->setDocument($document);
        };

        $this->collection()->each($setDocument);

        if ($this->isAttachedAccount()) {
            $this->getAccountManager()->getElements()->each($setDocument);
        }
    }

    /**
     * Returns a discount located by its code.
     *
     * @param $code
     * @return DiscountContract|null
     */
    public function getDiscount($code)
    {
        return $this->discounts->get($code);
    }

    /**
     * Add a discount.
     *
     * @param DiscountContract $discount
     * @return static
     */
    public function addDiscount(DiscountContract $discount)
    {
        $this->discounts->put($discount->getDiscountCode(), $discount);

        $addDiscount = function (CartProduct $item) use ($discount) {
            $item->addDiscount($discount);
        };

        $this->collection()->each($addDiscount);

        if ($this->isAttachedAccount()) {
            $this->getAccountManager()->getElements()->each($addDiscount);
        }

        return $this;
    }

    /**
     * Remove a discount.
     *
     * @param $code
     * @return static
     */
    public function removeDiscount($code)
    {
        $this->discounts->forget($code);

        $removeDiscount = function (CartProduct $item) use ($code) {
            $item->removeDiscount($code);
        };

        $this->collection()->each($removeDiscount);

        if ($this->isAttachedAccount()) {
            $this->getAccountManager()->getElements()->each($removeDiscount);
        }

        return $this;
    }

    /**
     * Return the document.
     *
     * @return DocumentContract
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Returns true if you have attached an account.
     *
     * @return bool
     */
    public function isAttachedAccount()
    {
        return ! is_null($this->accountManager);
    }

    /**
     * Clean the collection.
     *
     * @return  void
     * */
    public function clean()
    {
        $this->collection = collect();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'buyer' => [
                'key' => $this->buyer()->getUniqueIdentificationKey(),
                'properties' => $this->buyer()->getAdditionalAttributes(),
            ],
            'properties' => $this->getAdditionalAttributes()->toArray(),
            'discounts' => $this->discounts->toArray(),
            'document' => $this->getDocument()->toArray(),
            'account' => $this->isAttachedAccount() ? $this->getAccountManager()->toArray() : [],
        ]);
    }

    protected function makeSalableItem(ProductContract $product, $quantity)
    {

        return (new SalableItem($product, $quantity))->setDocument($this->document)->addDiscounts($this->discounts);
    }
}
