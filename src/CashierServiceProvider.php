<?php
/**
 * Created by enea dhack - 30/05/2017 05:08 PM.
 */

namespace Enea\Cashier\Provider;

use Enea\Cashier\Core\Calculator;
use Enea\Cashier\Core\CashierContract;
use Enea\Cashier\Core\Cashier;
use Illuminate\Support\ServiceProvider;

class CashierServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/cashier.php' => base_path('config/cashier.php')]);
        $this->app->bind(CashierContract::class, config('cashier.calculator', Cashier::class));

    }
}
