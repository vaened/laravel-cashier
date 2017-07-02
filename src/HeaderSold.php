<?php
/**
 * Created by enea dhack - 30/05/2017 08:17 PM
 */

namespace Enea\Cashier;


use Enea\Cashier\Contracts\HeaderSoldContract;
use Enea\Cashier\Contracts\SoldItemContract;

class HeaderSold extends BaseManager
{
    /**
     * @var HeaderSoldContract
     */
    protected $header;

    /**
     * HeaderSold constructor.
     * @param HeaderSoldContract $header
     */
    public function __construct(HeaderSoldContract $header)
    {
        parent::__construct( );
        $this->header = $header;
    }



    /**
     * Dump all elements of the database in a collection for later visualization or modification
     *
     * @return void
     */
    protected function buildElements( )
    {
        $this->header->elements()->each(function ( SoldItemContract $element ) {
            $this->addElementItem( $element );
        });
    }

    /**
     * Adds an item to the collection for later deletion or display
     *
     * @param SoldItemContract $element
     * @return void
     */
    protected function addElementItem(SoldItemContract $element )
    {
        $this->add($element->getItemKey( ), new SoldItem( $element, $this->getTaxPercentage( ) ));
    }

}