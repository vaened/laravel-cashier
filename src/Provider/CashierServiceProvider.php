<?php
/**
 * Created by enea dhack - 30/05/2017 05:08 PM
 */

namespace Enea\Cashier\Provider;


use Illuminate\Support\ServiceProvider;

class CashierServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes( [ __DIR__ . '/../Config/cashier.php' => base_path('config/cashier.php') ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}