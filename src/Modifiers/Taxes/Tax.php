<?php
/**
 * Created on 30/09/17 by enea dhack.
 */

namespace Enea\Cashier\Modifiers\Taxes;

use Enea\Cashier\IsJsonable;
use Enea\Cashier\Modifiers\TaxContract;

class Tax implements TaxContract
{
    use IsJsonable;

    /**
     * @var int
     */
    private $percentage;

    /**
     * @var bool
     */
    private $included;

    /**
     * IGV constructor.
     *
     * @param int $percentage
     * @param bool $included
     */
    public function __construct($percentage, $included)
    {
        $this->percentage = $percentage;
        $this->included = $included;
    }

    /**
     * Returns a tax instance.
     *
     * @param $percentage
     * @param $included
     * @return static
     */
    public static function make($percentage = 0, $included = false)
    {
        return new static($percentage, $included);
    }

    /**
     * {@inheritdoc}
     */
    public function getModifierValue()
    {
        return $this->percentage;
    }

    /**
     * {@inheritdoc}
     */
    public function isIncluded()
    {
        return $this->included;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'simple tax';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'modifier' => $this->getModifierValue(),
            'description' => $this->getDescription(),
            'is_percentage' => $this->isPercentage(),
            'is_included' => $this->isIncluded(),
        ];
    }

    /**
     * Returns true in case the discount is percentage.
     *
     * @return bool
     */
    public function isPercentage()
    {
        return true;
    }
}
