<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

use Enea\Cashier\Contracts\DocumentContract;
use Enea\Cashier\IsJsonable;

abstract class Document implements DocumentContract
{
    use IsJsonable;

    public static function create(): self
    {
        return new static();
    }

    public function getTaxes(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'key' => $this->getUniqueIdentificationKey(),
            'taxes' => $this->getTaxes(),
        ];
    }
}
