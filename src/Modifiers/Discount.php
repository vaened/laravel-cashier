<?php
/**
 * Created by enea dhack - 29/09/2017 12:02 PM.
 */

namespace Enea\Cashier\Modifiers;

use Enea\Cashier\Calculations\Percentager;
use Enea\Cashier\HasProperties;
use Enea\Cashier\IsJsonable;

class Discount implements DiscountContract
{
    use IsJsonable, HasProperties;

    protected string $code;

    protected string $description;

    protected float $discount;

    protected bool $percentage;

    public function __construct(
        string $code,
        float $discount,
        string $description,
        bool $percentage = true,
        array $properties = []
    ) {
        $this->code = $code;
        $this->description = $description;
        $this->discount = $discount;
        $this->percentage = $percentage;
        $this->setProperties($properties);
    }

    public static function percentage(float $percentage, array $properties = []): self
    {
        return new static(static::generateCode(), $percentage, 'discount percentage', true, $properties);
    }

    public static function value(float $discount, array $properties = []): self
    {
        return new static(static::generateCode(), $discount, 'discount value', false, $properties);
    }

    protected static function generateCode(): string
    {
        return hash('adler32', microtime(true), false);
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDiscountCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function extract(float $total): float
    {
        if (! $this->percentage) {
            return $this->discount;
        }

        return Percentager::excluded($total, $this->discount)->calculate();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'code' => $this->getDiscountCode(),
            'description' => $this->getDescription(),
            'properties' => $this->getProperties(),
        ];
    }
}
