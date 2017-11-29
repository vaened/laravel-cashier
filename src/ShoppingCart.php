<?php
/**
 * Created by enea dhack - 30/05/2017 03:31 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\AccountContract;
use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Contracts\SalableContract;
use Enea\Cashier\Documents\Free as FreeDocument;
use Enea\Cashier\Exceptions\IrreplaceableDetailItemException;
use Enea\Cashier\Exceptions\OneAccountAtTimeException;
use Enea\Cashier\Modifiers\DiscountContract;
use Illuminate\Support\Collection;

class ShoppingCart extends BaseManager
{
    /**
     * The account attached to the cart.
     *
     * @var AccountManager
     * */
    protected $account;

    /**
     * The payment document.
     *
     * @var DocumentContract
     * */
    protected $document;

    /**
     * @var Collection
     */
    protected $discounts;

    /**
     * ShoppingCart constructor.
     *
     * @param BuyerContract $buyer
     * @param DocumentContract $document
     * @param Collection $discounts
     */
    public function __construct(BuyerContract $buyer, DocumentContract $document = null, Collection $discounts = null)
    {
        parent::__construct($buyer);
        $this->discounts = $discounts ?: collect();
        $this->setDocument($document ?: FreeDocument::make());
    }

    /**
     * Attaches an account to pay and limits the elements to the detail of said account.
     *
     * @param AccountContract $account
     * @throws OneAccountAtTimeException
     * @return ShoppingCart
     */
    public function attach(AccountContract $account)
    {
        if ($this->isAttachedAccount()) {
            throw new OneAccountAtTimeException();
        }
        $this->clean();
        $this->account = new AccountManager($account, $this->document, $this->discounts);
        return $this;
    }

    /**
     * Unlink car account and clean all items.
     *
     * @throws OneAccountAtTimeException
     * @return ShoppingCart
     */
    public function detach()
    {
        if ($this->isAttachedAccount()) {
            $this->account = null;
            $this->clean();
        }

        return $this;
    }

    /**
     * Add a new item to the collection and return true if successful, if the buyer
     * has implemented the 'DetailedStaticContract' interface,
     * you will not be able to use this method.
     *
     * @param SalableContract $salable
     * @param int $quantity
     * @return bool
     */
    public function push(SalableContract $salable, $quantity = 1)
    {
        if ($this->isAttachedAccount()) {
            throw new IrreplaceableDetailItemException();
        }

        if ($has = ! $this->has($salable->getItemKey())) {
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
        if ($has = $this->getAccount()->has($key)) {
            $element = $this->getAccount()->find($key);
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
        $this->getAccount()->getElements()->each(function (AccountElement $element) {
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
    public function getAccount()
    {
        return $this->account;
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
        $setDocument = function (BaseSalableItem $item) use ($document) {
            $item->setDocument($document);
        };

        $this->collection()->each($setDocument);

        if ($this->isAttachedAccount()) {
            $this->getAccount()->getElements()->each($setDocument);
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

        $addDiscount = function (BaseSalableItem $item) use ($discount) {
            $item->addDiscount($discount);
        };

        $this->collection()->each($addDiscount);

        if ($this->isAttachedAccount()) {
            $this->getAccount()->getElements()->each($addDiscount);
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

        $removeDiscount = function (BaseSalableItem $item) use ($code) {
            $item->removeDiscount($code);
        };

        $this->collection()->each($removeDiscount);

        if ($this->isAttachedAccount()) {
            $this->getAccount()->getElements()->each($removeDiscount);
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
        return ! is_null($this->account);
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
                'key' => $this->buyer()->getBuyerKey(),
                'properties' => $this->buyer()->getAdditionalAttributes(),
            ],
            'properties' => $this->getAdditionalAttributes()->toArray(),
            'discounts' => $this->discounts->toArray(),
            'document' => $this->getDocument()->toArray(),
            'account' => $this->isAttachedAccount() ? $this->getAccount()->toArray() : [],
        ]);
    }

    /**
     * Build a salable item.
     *
     * @param SalableContract $salable
     * @param $quantity
     * @return SalableItem
     */
    protected function makeSalableItem(SalableContract $salable, $quantity)
    {
        return (new SalableItem($salable, $quantity))->setDocument($this->document)->addDiscounts($this->discounts);
    }
}
