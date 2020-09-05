<?php
/**
 * Created by enea dhack - 01/08/2020 0:59.
 */

namespace Enea\Cashier\Calculations;

use Enea\Cashier\Modifiers\TaxContract;

class PriceEvaluator
{
    private float $unitPrice;

    private float $unitPriceIncluded;

    private float $unitPriceExcluded;

    private float $toIncorporate;

    public function __construct(float $unitPrice, array $taxes, array $taxNamesToUse)
    {
        $this->unitPrice = $unitPrice;
        $this->unitPriceIncluded = $this->getPriceIncluded($unitPrice, $taxes);
        $this->unitPriceExcluded = $this->getPriceExcluded($this->getGrossUnitPrice(), $taxes);
        $this->toIncorporate = $this->getTaxesToIncorporate($this->getGrossUnitPrice(), $taxes, $taxNamesToUse);
    }

    public function getUnitPrice(): float
    {
        return $this->getGrossUnitPrice() + $this->toIncorporate;
    }

    public function getGrossUnitPrice(): float
    {
        return $this->unitPrice - $this->unitPriceIncluded;
    }

    public function getNetUnitPrice(): float
    {
        return $this->unitPrice + $this->unitPriceExcluded;
    }

    private function getPriceIncluded(float $unitPrice, array $taxes): float
    {
        $included = $this->getTaxesFromStatus($taxes, true);
        $percentage = $this->getTotalPercentage($included);
        return Percentager::included($unitPrice, $percentage)->calculate();
    }

    private function getPriceExcluded(float $grossUnitPrice, array $taxes): float
    {
        $excluded = $this->getTaxesFromStatus($taxes, false);
        $percentage = $this->getTotalPercentage($excluded);
        return Percentager::excluded($grossUnitPrice, $percentage)->calculate();
    }

    private function getTaxesToIncorporate(float $grossUnitPrice, array $taxes, array $taxNamesToUse): float
    {
        $included = $this->getTaxesFromStatus($taxes, true);
        $necessaryTaxes = $this->getAllButThese($included, $taxNamesToUse);
        $percentage = $this->getTotalPercentage($necessaryTaxes);
        return Percentager::excluded($grossUnitPrice, $percentage)->calculate();
    }

    private function getAllButThese(array $taxes, array $taxNamesToUse): array
    {
        return array_filter($taxes, fn(TaxContract $tax) => ! in_array($tax->getName(), $taxNamesToUse));
    }

    private function getTotalPercentage(array $taxes): float
    {
        return array_reduce($taxes, fn(float $acc, TaxContract $tax): float => $acc + $tax->getPercentage(), 0.0);
    }

    private function getTaxesFromStatus(array $taxes, bool $status): array
    {
        return array_filter($taxes, fn(TaxContract $tax): bool => $tax->isIncluded() == $status);
    }
}
