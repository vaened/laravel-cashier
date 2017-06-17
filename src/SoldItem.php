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
        parent::__construct($sold);
        $this->setQuantity( $sold->getQuantity( ) );
        $this->sold = $sold;
    }

}