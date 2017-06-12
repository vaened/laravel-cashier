<?php
/**
 * Created by enea dhack - 12/06/2017 02:12 PM
 */

namespace Enea\Tests;


use Enea\Cashier\Contracts\InvoiceContract;
use Enea\Cashier\SalableItem;
use Enea\Cashier\ShoppingCard;
use Enea\Tests\Models\PreinvoiceModel;

class SaleWithPreDetailTest extends TestCase
{

    /**
     * @test
     */
    function can_load_the_detail_of_a_header()
    {
        $card = $this->getShoppingCard( );

        $this->assertTrue($card->find(5) != null );

        $this->assertTrue($card->find(5) instanceof SalableItem);
        $this->assertSame($card->collection()->count(), 2);

        dd($card->toJson());
    }

    protected function getShoppingCard( InvoiceContract $invoice = null )
    {
        $buyer = new PreinvoiceModel(['id' => 10 ]);
        return new ShoppingCard($buyer, $invoice);
    }



}