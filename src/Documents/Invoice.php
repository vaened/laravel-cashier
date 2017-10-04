<?php
/**
 * Created on 01/10/17 by enea dhack.
 */

namespace Enea\Cashier\Documents;

use Enea\Cashier\Contracts\BusinessOwner;
use Enea\Cashier\Modifiers\Taxes\IGV;

class Invoice extends Document
{
    /**
     * @var bool
     * */
    protected $includedTax = false;

    /**
     * Invoice constructor.
     *
     * @param BusinessOwner $owner
     */
    public function __construct(BusinessOwner $owner = null)
    {
        parent::__construct($owner);
        $this->withoutTaxIncluded();
    }

    /**
     * Returns a new instance.
     *
     * @param BusinessOwner|null $owner
     * @return static
     */
    public static function make(BusinessOwner $owner = null)
    {
        return new static($owner);
    }

    /**
     * Includes IGV in price.
     *
     * @return static
     */
    public function withTaxIncluded()
    {
        $this->includedTax = true;
        return $this;
    }

    /**
     * Does not include IGV in price.
     *
     * @return static
     */
    public function withoutTaxIncluded()
    {
        $this->includedTax = false;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeyDocument()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxes()
    {
        return collect([
            IGV::make(18, $this->includedTax),
        ]);
    }
}
