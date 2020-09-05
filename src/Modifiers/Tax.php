<?php
/**
 * Created on 30/09/17 by enea dhack.
 */

namespace Enea\Cashier\Modifiers;

use Enea\Cashier\IsJsonable;

class Tax implements TaxContract
{
    use IsJsonable;

    private float $percentage;

    private bool $included;

    private string $name;

    public function __construct(string $name, float $percentage, bool $included = true)
    {
        $this->name = $name;
        $this->percentage = $percentage;
        $this->included = $included;
    }

    public static function create(string $name, float $percentage): self
    {
        return self::included($name, $percentage);
    }

    public static function excluded(string $name, float $percentage): self
    {
        return new static($name, $percentage, false);
    }

    public static function included(string $name, float $percentage): self
    {
        return new static($name, $percentage, true);
    }

    public function setIncluded(bool $included): self
    {
        $this->included = $included;
        return $this;
    }

    public function include(): self
    {
        return $this->setIncluded(true);
    }

    public function exclude(): self
    {
        return $this->setIncluded(false);
    }

    public function isIncluded(): bool
    {
        return $this->included;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'percentage' => $this->getPercentage(),
            'is_included' => $this->isIncluded(),
        ];
    }
}
