<?php
/**
 * Created by enea dhack - 27/07/2020 18:31.
 */

namespace Enea\Cashier\Items;

use Enea\Cashier\Contracts\QuotedProductContract;
use Enea\Cashier\Modifiers\TaxContract;

final class QuotedProductCartItem extends CartItem
{
    private QuotedProductContract $quoted;

    public function __construct(QuotedProductContract $quoted)
    {
        parent::__construct($quoted, $quoted->getQuantity(), $quoted->getTaxes(), $quoted->getDiscounts());
        $this->applyTaxes($quoted);
        $this->quoted = $quoted;
    }

    public function getProduct(): QuotedProductContract
    {
        return $this->quoted;
    }

    public function toSell(): ProductCartItem
    {
        return new ProductCartItem($this->getProduct(), $this->getQuantity(), [], $this->quoted->getDiscounts());
    }

    private function applyTaxes(QuotedProductContract $quoted)
    {
        $names = array_map(fn(TaxContract $tax): string => $tax->getName(), $quoted->getTaxes());
        $this->getCalculator()->applyTaxes($names);
    }
}
