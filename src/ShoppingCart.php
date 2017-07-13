<?php
/**
 * Created by enea dhack - 30/05/2017 03:31 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\AccountContract;
use Enea\Cashier\Contracts\AccountElementContract;
use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\Contracts\SalableContract;
use Enea\Cashier\Exceptions\IrreplaceableDetailItemException;
use Enea\Cashier\Exceptions\OneAccountAtTimeException;
use Illuminate\Support\Collection;

class ShoppingCart extends BaseManager
{
    /**
     * Customer cart owner.
     *
     * @var BuyerContract
     */
    protected $buyer;

    /**
     * The account attached to the cart.
     *
     * @var AccountContract
     * */
    protected $account;

    /**
     * The payment document.
     *
     * @var DocumentContract
     * */
    protected $document;

    /**
     * ShoppingCart constructor.
     *
     * @param BuyerContract $buyer
     * @param DocumentContract $document
     */
    public function __construct(BuyerContract $buyer, DocumentContract $document = null)
    {
        parent::__construct();

        $this->buyer = $buyer;

        if (! is_null($document)) {
            $this->setPaymentDocument($document);
        }
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

        $this->account = $account;

        $this->account->getElements()->each(function (AccountElementContract $element) {
            $this->storage->put($element->getItemKey(), new AccountElement($element, $this->getImpostPercentage()));
        });

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

        $item = new SalableItem($salable, $quantity, $this->getImpostPercentage());

        if ($has = ! $this->hasItem($salable->getItemKey())) {
            $this->add($salable->getItemKey(), $item);
        }

        return $has;
    }

    /**
     * Passes an item from the store to the collection and returns true on success.
     *
     * @param string $key
     *
     * @return bool
     */
    public function pull($key)
    {
        if ($has = $this->storage()->has($key)) {
            $element = $this->getAccountElement($key);
            $this->add($element->getKey(), new SalableItem($element->getSalable(), $element->getQuantity(), $this->getImpostPercentage()));
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
        $this->storage()->each(function (AccountElement $element) {
            $this->pull($element->getKey());
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
        if ($has = $this->hasItem($key)) {
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
    public function hasItem($key)
    {
        return isset($this->collection()[$key]);
    }

    /**
     * Returns buyer instance.
     *
     * @return BuyerContract
     */
    public function buyer()
    {
        return $this->buyer;
    }

    /**
     * Returns the attached account.
     *
     * @return AccountContract
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Returns storage.
     *
     * @return Collection
     * */
    public function storage()
    {
        return $this->storage;
    }

    /**
     * Set the payment document and extract tex percentage.
     *
     * @param DocumentContract $document
     * @return int
     */
    public function setPaymentDocument(DocumentContract $document)
    {
        $this->document = $document;
        $this->impostPercentage = $document->getTaxPercentageAttribute();

        $this->collection()->each(function (SalableItem $item) {
            $item->setImpostPercentage($this->getImpostPercentage());
        });
    }

    /**
     * Return the document type.
     *
     * @return DocumentContract
     */
    public function getPaymentDocument()
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
     * Return an item belonging to the attached account.
     *
     * @param string $key
     * @return AccountElement|null
     */
    public function getAccountElement($key)
    {
        return $this->storage()->get($key);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $account = null;

        if ($this->isAttachedAccount()) {
            $account = [
                'key' => $this->getAccount()->getKeyIdentification(),
                'properties' => $this->getAccount()->getCustomProperties(),
            ];
        }

        return array_merge(parent::toArray(), [
            'buyer' => [
                'key' => $this->buyer()->getBuyerKey(),
                'properties' => $this->buyer()->getCustomProperties()
            ],
            'storage' => $this->storage()->toArray(),
            'account' => $account
        ]);
    }
}
