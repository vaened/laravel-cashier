<?php
/**
 * Created by enea dhack - 10/08/2020 0:41.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Calculations\Taxed;
use Enea\Cashier\Modifiers\Tax;
use Enea\Tests\TestCase;

class TaxedTest extends TestCase
{
    public function test_variability_of_tax_statuses(): void
    {
        $tax = new Tax('EXAMPLE', 10.0, false);

        $tax->include();
        $this->assertTrue($tax->isIncluded());

        $tax->exclude();
        $this->assertFalse($tax->isIncluded());

        $tax->setIncluded(true);
        $this->assertTrue($tax->isIncluded());
    }

    public function test_taxed(): void
    {
        $taxed = new Taxed(Tax::excluded('EXAMPLE', 10.0), 400.0);
        $this->assertEquals(40, $taxed->getTotal());
    }

    public function test_help_methods_to_obtain_the_tax(): void
    {
        $taxed = new Taxed(Tax::excluded('EXAMPLE', 10.0), 400.0);

        $this->assertEquals('EXAMPLE', $taxed->getName());
        $this->assertEquals(10, $taxed->getPercentage());
        $this->assertEquals(false, $taxed->isIncluded());
    }

    public function test_transform_taxed_to_array(): void
    {
        $taxed = new Taxed(Tax::excluded('EXAMPLE', 10.0), 400.0);

        $this->assertEquals([
            'name' => 'EXAMPLE',
            'percentage' => 10.0,
            'is_included' => false,
            'total' => 40.0,
        ], $taxed->toArray());
    }
}
