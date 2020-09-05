<?php
/**
 * Created by enea dhack - 10/08/2020 14:37.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Calculations\Percentager;
use Enea\Tests\TestCase;

class PercentagerTest extends TestCase
{
    public function test_percentage_included(): void
    {
        $percentager = Percentager::included(100, 18);
        $this->assertEquals(15.254237288135585, $percentager->calculate());
    }

    public function test_percentage_excluded(): void
    {
        $percentager = Percentager::excluded(100, 18);
        $this->assertEquals(18.0, $percentager->calculate());
    }
}
