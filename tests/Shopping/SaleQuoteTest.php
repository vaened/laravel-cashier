<?php
/**
 * Created by enea dhack - 17/08/2020 19:43.
 */

namespace Enea\Tests\Shopping;

use Enea\Cashier\Documents\Invoice;
use Enea\Cashier\Modifiers\Discount;
use Enea\Cashier\ShoppingCart;
use Enea\Cashier\Taxes;
use Enea\Tests\DataBaseTestCase;
use Enea\Tests\Models\Client;
use Enea\Tests\Models\Quote;

class SaleQuoteTest extends DataBaseTestCase
{
    public function test_pull_product_from_the_quote(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $shoppingCart->pull(2);

        $this->assertNotNull($shoppingCart->find(2));
    }

    public function test_pull_all_only_filters_missing_products(): void
    {
        $shoppingCart = $this->getShoppingCart()->pullAll();

        $this->assertCount(2, $shoppingCart->products());
    }

    public function test_detach_a_quote(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $shoppingCart->detach();

        $this->assertNull($shoppingCart->getQuoteManager());
        $this->assertFalse($shoppingCart->hasQuote());
    }

    public function test_separating_a_quote_restarts_the_product_collection(): void
    {
        $shoppingCart = $this->getShoppingCart();
        $shoppingCart->detach();

        $this->assertEmpty($shoppingCart->products());
    }

    public function test_attach_a_quote(): void
    {
        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create([Taxes::IGV]));
        $shoppingCart->attach(Quote::find(2));

        $this->assertNotNull($shoppingCart->getQuoteManager());
        $this->assertTrue($shoppingCart->hasQuote());
    }

    public function test_attach_a_quote_restarts_the_product_collection(): void
    {
        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create([Taxes::IGV]));
        $shoppingCart->attach(Quote::find(2));

        $this->assertEmpty($shoppingCart->products());
    }

    public function test_pull_all_products_from_the_quote(): void
    {
        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create([Taxes::IGV]));
        $shoppingCart->attach(Quote::find(2))->pullAll();

        $this->assertCount(2, $shoppingCart->products());
    }

    public function test_attach_the_quote_to_the_array(): void
    {
        $shoppingCart = $this->getShoppingCart()->toArray();
        $this->assertNotNull($shoppingCart['quote']);
    }

    public function getShoppingCart(): ShoppingCart
    {
        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create([Taxes::IGV]));
        $shoppingCart->attach(Quote::find(1));

        $keyboard = $shoppingCart->pull(1);
        $keyboard->addDiscount(Discount::value(20)->setCode('FIRST'));

        return $shoppingCart;
    }
}
