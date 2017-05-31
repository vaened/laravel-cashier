<?php
/**
 * Created by enea dhack - 30/05/2017 08:07 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\SoldItemContract;
use Illuminate\Database\Eloquent\Model;

class SoldItem extends BaseItem
{
    /**
     * @var SoldItemContract
     */
    protected $sold;

    /**
     * SoldItem constructor.
     *
     * @param SoldItemContract $sold
     */
    public function __construct( SoldItemContract $sold )
    {
        $this->setQuantity( $sold->getQuantity( ) );
        $this->sold = $sold;
    }

    /**
     * Returns the object of the calculator with the requested parameters
     *
     * @return Calculator
     */
    protected function calculatorConfiguration( ): Calculator
    {
        // TODO: Implement calculatorConfiguration() method.
    }

    /**
     * Return an instance of the model that represents the product
     *
     * @return Model
     */
    protected function model(): Model
    {
        return $this->sold;
    }
}