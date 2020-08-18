<?php
/**
 * Created by enea dhack - 16/08/2020 18:27.
 */

namespace Enea\Tests\Shopping;

use Enea\Cashier\Documents\Invoice;
use Enea\Cashier\Exceptions\MissingAccountException;
use Enea\Cashier\Exceptions\NotFoundProductException;
use Enea\Cashier\Exceptions\UnrepeatableProductException;
use Enea\Cashier\ShoppingCart;
use Enea\Tests\DataBaseTestCase;
use Enea\Tests\Models\Client;
use Enea\Tests\Models\Product;
use Enea\Tests\Models\Quote;

class ShoppingCartValidationTest extends DataBaseTestCase
{
    public function test_register_repeated_product_throws_an_exception(): void
    {
        $this->expectException(UnrepeatableProductException::class);
        $this->expectExceptionMessage("The product 'Keyboard K530-rgb' cannot be added more than once");

        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create());
        $shoppingCart->push(Product::find(1));
        $shoppingCart->push(Product::find(1));
    }

    public function test_pull_a_product_without_a_quote_throws_an_exception(): void
    {
        $this->expectException(MissingAccountException::class);
        $this->expectExceptionMessage("No account available");

        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create());
        $shoppingCart->pull(1);
    }

    public function test_pull_all_products_without_a_quote_throw_an_exception()
    {
        $this->expectException(MissingAccountException::class);
        $this->expectExceptionMessage("No account available");

        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create());
        $shoppingCart->pullAll();
    }

    public function test_pull_a_product_that_doesnt_exist_throws_an_exception(): void
    {
        $this->expectException(NotFoundProductException::class);
        $this->expectExceptionMessage("Product not found with ID");

        $shoppingCart = new ShoppingCart(Client::find(1), Invoice::create());
        $shoppingCart->attach(Quote::find(1));
        $shoppingCart->pull(4444);
    }
}
