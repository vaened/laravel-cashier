<?php
/**
 * Created by enea dhack - 12/06/17 10:17 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\DocumentContract;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;

class ShoppingManager
{
    /**
     * @var SessionManager
     */
    protected $session;

    /**
     * Main session key.
     *
     * @var string
     * */
    protected $key;

    /**
     * ShoppingManager constructor.
     *
     * @param SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Start a new shopping cart in session and return the session ID.
     *
     * @param BuyerContract $buyer
     * @param DocumentContract $document
     *
     * @return ShoppingCart
     */
    public function initialize(BuyerContract $buyer, DocumentContract $document = null)
    {
        $shopping = new ShoppingCart($buyer, $document);

        if (! $this->isInitiated()) {
            $this->session->put($this->key(), collect());
        }

        $this->attach($shopping);

        return $shopping;
    }

    /**
     * Return the shopping cart from the session.
     *
     * @param string $_token
     *
     * @return ShoppingCart|null
     */
    public function find($_token)
    {
        if (! $this->isInitiated()) {
            return;
        }

        return $this->carts()->get($_token);
    }

    /**
     * Delete a specific shopping cart and return true if it was found.
     *
     * @param string $_token
     *
     * @return bool
     */
    public function drop($_token)
    {
        if (! $this->isInitiated()) {
            return false;
        }

        $carts = $this->carts();

        if ($has = $carts->has($_token)) {
            $carts->forget($_token);
        }

        return $has;
    }

    /**
     * Delete all items from the session.
     *
     * @return void
     */
    public function flush()
    {
        $this->session->forget($this->key());
    }

    /**
     * Add a new shopping cart to the session.
     *
     * @param ShoppingCart $shopping
     *
     * @return void
     */
    protected function attach(ShoppingCart $shopping)
    {
        $this->carts()->put($shopping->token(), $shopping);
    }

    /**
     * Returns the main key of the session.
     *
     * @return string
     */
    protected function key()
    {
        return $this->key ?: $this->key = config('cashier.session_key', 'default_laravel_shopping_session_key');
    }

    /**
     * Returns true in case the session handler has been initialized.
     *
     * @return bool
     */
    protected function isInitiated()
    {
        return  $this->session->has($this->key());
    }

    /**
     * Returns all shopping cars in session.
     *
     * @return Collection|null
     */
    protected function carts()
    {
        return $this->session->get($this->key());
    }
}
