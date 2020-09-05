<?php
/**
 * Created by enea dhack - 12/08/2020 16:45.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Calculations\PriceEvaluator;
use Enea\Cashier\Modifiers\Tax;
use Enea\Tests\TestCase;

class PriceTest extends TestCase
{
    public function test_price_including_tax(): void
    {
        $taxes = [Tax::included('TAX1', 10), Tax::included('TAX2', 5)];
        $evaluator = new PriceEvaluator(679.21, $taxes, ['TAX1', 'TAX2']);

        $this->assertEquals(590.61739130434, $evaluator->getGrossUnitPrice());
        $this->assertEquals(590.61739130434, $evaluator->getUnitPrice());
        $this->assertEquals(679.21, $evaluator->getNetUnitPrice());
    }

    public function test_price_without_taxes_included(): void
    {
        $taxes = [Tax::excluded('TAX1', 10), Tax::excluded('TAX2', 5)];
        $evaluator = new PriceEvaluator(679.21, $taxes, ['TAX1', 'TAX2']);

        $this->assertEquals(679.21, $evaluator->getGrossUnitPrice());
        $this->assertEquals(679.21, $evaluator->getUnitPrice());
        $this->assertEquals(781.0915, $evaluator->getNetUnitPrice());
    }

    public function test_price_with_mixed_tax(): void
    {
        $taxes = [Tax::included('INCLUDED', 10), Tax::excluded('EXCLUDED', 5)];
        $evaluator = new PriceEvaluator(679.21, $taxes, ['INCLUDED', 'EXCLUDED']);

        $this->assertEquals(617.46363636364, $evaluator->getGrossUnitPrice());
        $this->assertEquals(617.46363636364, $evaluator->getUnitPrice());
        $this->assertEquals(710.08318181818, $evaluator->getNetUnitPrice());
    }

    public function test_price_with_optional_tax(): void
    {
        $taxes = [Tax::included('REQUIRED', 10), Tax::included('OPTIONAL', 5)];
        $evaluator = new PriceEvaluator(679.21, $taxes, ['REQUIRED']);

        $this->assertEquals(590.61739130435, $evaluator->getGrossUnitPrice());
        $this->assertEquals(620.14826086957, $evaluator->getUnitPrice());
        $this->assertEquals(679.21, $evaluator->getNetUnitPrice());
    }
}
