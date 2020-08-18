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

    private array $taxes;

    public function __construct(array $taxes)
    {
        $this->taxes = $taxes;
    }

    public static function create(array $taxes = []): self
    {
        return new static($taxes);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'key' => $this->getUniqueIdentificationKey(),
            'taxes' => $this->taxesToUse(),
        ];
    }

    public function taxesToUse(): array
    {
        return $this->taxes;
    }
}
