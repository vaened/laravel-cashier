<?php
/**
 * Created by enea dhack - 30/05/2017 04:42 PM.
 */

namespace Enea\Tests;

use Enea\Cashier\CashierServiceProvider;
use Enea\Cashier\Calculations\Calculator;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->config($this->app->make('config'));
    }

    protected function config(ConfigContract $config): void
    {
        $config->set('cashier.decimals', 3);
        $config->set('cashier.cashier', Calculator::class);
    }

    protected function getPackageProviders($app)
    {
        return [CashierServiceProvider::class];
    }
}
