<?php
/**
 * Created by enea dhack - 30/05/2017 04:42 PM.
 */

namespace Enea\Tests;

use Enea\Cashier\ShoppingManager;
use Enea\Tests\Documents\Invoice;
use Enea\Tests\Models\Client;
use Enea\Tests\Models\DiscountableProduct;
use Enea\Tests\Models\Preinvoice;
use Enea\Tests\Models\Product;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function salable(array $attributes = array())
    {
        $attributes = array_merge([
            'price' => 123.45,
            'description' => 'some description',
            'id' => 1,
            'taxable' => true
        ], $attributes);
        return new Product($attributes);
    }

    protected function discountableProduct(array $attributes = array())
    {
        $attributes = array_merge([
            'price' => 123.45,
            'description' => 'some description',
            'id' => 1,
            'taxable' => true,
            'discount' => 5,
        ], $attributes);

        return new DiscountableProduct($attributes);
    }

    protected function getManager()
    {
        return new ShoppingManager($this->app['session']);
    }

    protected function getShoppingCart(ShoppingManager $manager, $client_id = 10000)
    {
        $client = new Client(['id' => $client_id]);

        $document = new Invoice();

        return  $manager->initialize($client, $document);
    }

    protected function getPreinvoice(array $attributes = array())
    {
        $attributes = array_merge([
            'id' => 11234,
        ], $attributes);

        return new Preinvoice($attributes);
    }
}
