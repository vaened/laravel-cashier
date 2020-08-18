<?php
/**
 * Created by enea dhack - 30/05/2017 05:08 PM.
 */

namespace Enea\Cashier;

use Enea\Cashier\Calculations\Calculator;
use Enea\Cashier\Calculations\CalculatorContract;
use Illuminate\Support\ServiceProvider;

class CashierServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/cashier.php' => base_path('config/cashier.php')]);
        $this->app->bind(CalculatorContract::class, config('cashier.calculator', Calculator::class));
    }
}
