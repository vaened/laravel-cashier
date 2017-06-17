<?php
/**
 * Created by enea dhack - 16/06/17 10:23 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\AccountElementContract;
use Enea\Cashier\Contracts\SalableContract;
use Illuminate\Database\Eloquent\Model;

class AccountElement extends BaseItem
{

    /**
     * @var AccountElementContract
     */
    private $salable;

    public function __construct( AccountElementContract $salable, int $impostPercentage = 0 )
    {
        $this->salable = $salable;
        parent::setQuantity($salable->getQuantity());
        parent::setImpostPercentage( $impostPercentage );
    }

    /**
     * Returns identification
     *
     * @return int|string
     * */
    public function getKey()
    {
        return $this->salable->getItemKey( );
    }

    /**
     * @return SalableContract
     */
    public function getSalable( ): SalableContract
    {
        return $this->salable;
    }

    /**
     * Get base price for item
     *
     * @return float
     */
    protected function getBasePrice(): float
    {
        return $this->salable->getBasePrice();
    }

    /**
     * Return an instance of the model that represents the product
     *
     * @return Model
     */
    protected function model(): Model
    {
        return $this->salable;
    }

}