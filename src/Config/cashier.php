<?php
/**
 * Created by enea dhack - 30/05/2017 05:01 PM
 */

return [

     // Number of decimal places to be taken into account for the calculator
    'decimals' => 3,

    // Session key where all shopping carts will be stored
    'session_key' => 'LARAVEL_SOPPING_MANAGER_SESSION',

    // Class that is responsible for calculating all values
    'calculator' => null,  // default  \Enea\Cashier\Calculator::class,

];
