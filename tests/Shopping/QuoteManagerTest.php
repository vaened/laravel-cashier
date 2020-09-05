<?php
/**
 * Created by enea dhack - 08/08/2020 15:51.
 */

namespace Enea\Tests\Shopping;

use Enea\Cashier\QuoteManager;
use Enea\Tests\DataBaseTestCase;
use Enea\Tests\Models\Quote;

class QuoteManagerTest extends DataBaseTestCase
{
    public function test_id_is_equal_to_quote_id(): void
    {
        $this->assertEquals('1', $this->getQuote()->getUniqueIdentificationKey());
    }

    public function test_find_products(): void
    {
        $quote = $this->getQuote();

        $this->assertEquals('Keyboard K530-rgb', $quote->find(1)->getShortDescription());
        $this->assertEquals('Chest Backpack', $quote->find(2)->getShortDescription());
    }

    public function test_return_the_attached_quote(): void
    {
        $manager = $this->getQuote();
        $this->assertEquals('1', $manager->getQuote()->getUniqueIdentificationKey());
    }

    public function test_quote_totals(): void
    {
        $quote = $this->getQuote();

        $this->assertEquals(694.915, $quote->getSubtotal());
        $this->assertEquals(125.085, $quote->getTotalTaxes());
        $this->assertEquals(34.745, $quote->getTotalDiscounts());
        $this->assertEquals(785.255, $quote->getTotal());
    }

    public function test_transform_quote_to_array(): void
    {
        $quote = $this->getQuote()->toArray();

        $this->assertCount(2, $quote['products']);

        $this->assertEquals([
            'id' => '1',
            'properties' => [],
            'subtotal' => 694.915,
            'total' => 785.255,
            'total_taxes' => 125.085,
            'total_discounts' => 34.745,
        ], [
            'id' => $quote['id'],
            'properties' => $quote['properties'],
            'subtotal' => $quote['subtotal'],
            'total' => $quote['total'],
            'total_taxes' => $quote['total_taxes'],
            'total_discounts' => $quote['total_discounts'],
        ]);
    }

    private function getQuote(): QuoteManager
    {
        return new QuoteManager(Quote::find(1));
    }
}
