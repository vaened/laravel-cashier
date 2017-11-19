<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\AccountContract;
use Enea\Cashier\Contracts\AccountElementContract;
use Enea\Cashier\Contracts\DocumentContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

class AccountManager implements Arrayable, Jsonable
{
    use IsJsonable;

    /**
     * @var AccountContract
     */
    protected $account;

    /**
     * @var Collection<AccountElement>
     * */
    protected $elements;

    /**
     * @var DocumentContract
     */
    protected $document;

    /**
     * @var Collection<DiscountContract>
     */
    protected $discounts;

    /**
     * AccountHandler constructor.
     *
     * @param AccountContract $account
     * @param DocumentContract $document
     * @param Collection $discounts
     */
    public function __construct(AccountContract $account, DocumentContract $document, Collection $discounts)
    {
        $this->account = $account;
        $this->document = $document;
        $this->discounts = $discounts;

        $this->initialize();
    }

    /**
     * find a element in account.
     *
     * @param string|int $key
     * @return AccountElement|null
     */
    public function find($key)
    {
        return $this->elements->get($key);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param string|int $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->elements[$key]);
    }

    /**
     * Returns identification one in the database - primary key.
     *
     * @return string
     */
    public function getKeyIdentification()
    {
        return $this->getModel()->getKeyIdentification();
    }

    /**
     * Returns all account elements.
     *
     * @return Collection
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Returns the account.
     *
     * @return AccountContract
     */
    public function getModel()
    {
        return $this->account;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'key' => $this->getModel()->getKeyIdentification(),
            'properties' => $this->getModel()->getAdditionalAttributes(),
            'elements' => $this->getElements()->toArray(),
        ];
    }

    /**
     * Build a account element.
     *
     * @param AccountElementContract $element
     * @return AccountElement
     */
    protected function makeElement(AccountElementContract $element)
    {
        return new AccountElement($element);
    }

    /**
     * Initialize variables.
     *
     * @return void
     */
    protected function initialize()
    {
        $elements = collect();

        $this->getModel()->getElements()->each(function (AccountElementContract $element) use ($elements) {
            $elements->put($element->getItemKey(), $this->makeElement($element)->setDocument($this->document)->addDiscounts($this->discounts));
        });

        $this->elements = $elements;
    }
}
