<?php
/**
 * Created by enea dhack - 12/06/17 10:17 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\BuyerContract;
use Enea\Cashier\Contracts\InvoiceContract;
use Illuminate\Session\SessionManager;

class ShoppingManager
{

    /**
     * @var SessionManager
     */
    protected $session;

    protected const TOKEN_LENGTH = 20;

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
     * Start a new shopping cart in session and return the session ID
     *
     * @param BuyerContract $buyer
     * @param InvoiceContract $invoice
     * @return ShoppingCard
     */
    public function initialize( BuyerContract $buyer, InvoiceContract $invoice = null ): ShoppingCard
    {
        $shopping = new ShoppingCard($buyer, $invoice);

        $this->session->put($shopping->token(), $shopping );

        return $shopping;
    }

    /**
     * Return the shopping cart from the session
     *
     * @param $_token
     * @return ShoppingCard|null
     */
    public function find( $_token ): ?ShoppingCard
    {
        if($this->session->has($_token)) {
            $this->session->get($_token);
        }

        return null;
    }


}