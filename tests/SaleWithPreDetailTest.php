<?php
/**
 * Created by enea dhack - 12/06/2017 02:12 PM
 */

namespace Enea\Tests;


use Enea\Cashier\Contracts\InvoiceContract;
use Enea\Cashier\SalableItem;
use Enea\Cashier\ShoppingCard;
use Enea\Tests\Models\PreinvoiceModel;
use Enea\Tests\Models\Sales\SalableModel;

class SaleWithPreDetailTest extends TestCase
{

    /**
     * @expectedException \Enea\Cashier\Exceptions\IrreplaceableDetailItemException
     * @test
     * */
    function an_exception_is_thrown_if_you_try_to_add_an_item_that_is_not_in_storage()
    {
        $card = $this->getShoppingCard( );
        $card->push(new SalableModel(['id' => 'invalid']));
    }


    /**
     * @test
     */
    function can_load_the_detail_of_a_header()
    {
        $card = $this->getShoppingCard( )->dumpAllStorage( );

        $this->assertTrue($card->find(5) != null );

        $this->assertTrue($card->find(5) instanceof SalableItem);
        $this->assertSame($card->collection()->count(), 2);
    }



    protected function getShoppingCard( InvoiceContract $invoice = null )
    {
        $buyer = new PreinvoiceModel(['id' => 10 ]);
        return new ShoppingCard($buyer, $invoice);
    }



}