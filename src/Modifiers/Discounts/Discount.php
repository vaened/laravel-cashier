<?php
/**
 * Created by enea dhack - 29/09/2017 12:02 PM.
 */

namespace Enea\Cashier\Modifiers\Discounts;

use Enea\Cashier\Contracts\CalculableContract;
use Enea\Cashier\IsJsonable;
use Enea\Cashier\Modifiers\DiscountContract;
use Illuminate\Support\Collection;

class Discount implements DiscountContract
{
    use IsJsonable;

    /**
     * @var int|string
     */
    protected $key;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $value;

    /**
     * @var bool
     */
    protected $percentage;

    /**
     * @var array
     */
    protected $additionalAttributes;

    /**
     * Discount constructor.
     *
     * @param string|int $key
     * @param string $description
     * @param int $value
     * @param bool $percentage
     * @param Collection $additionalAttributes
     */
    public function __construct(
        $key,
        $description,
        $value,
        $percentage = true,
        Collection $additionalAttributes = null
    ) {
        $this->key = $key;
        $this->description = $description;
        $this->value = $value;
        $this->percentage = $percentage;
        $this->additionalAttributes = $additionalAttributes ?: collect();
    }

    /**
     * Returns a new instance.
     *
     * @param string $key
     * @param string $description
     * @param int|float $value
     * @param Collection $attributes
     * @return static
     */
    public static function make($key, $description, $value, Collection $attributes = null)
    {
        return new static($key, $description, $value, true, $attributes);
    }

    /**
     * Returns a new instance.
     *
     * @param $description
     * @param $percentage
     * @param Collection $attributes
     * @return static
     */
    public static function generate($description, $percentage, Collection $attributes = null)
    {
        return static::make(static::generateKey(), $description, $percentage, $attributes);
    }

    /**
     * Makes the discount value an absolute and only subtract the amount.
     *
     * @return static
     */
    public function withAbsoluteValue()
    {
        $this->percentage = false;
        return $this;
    }

    /**
     * Makes the discount value a percentage.
     *
     * @return static
     */
    public function withPercentageValue()
    {
        $this->percentage = true;
        return $this;
    }

    /**
     * Returns the discount key.
     *
     * @return int|string
     */
    public function getDiscountCode()
    {
        return $this->key;
    }

    /**
     * Returns the value that modifies the amount.
     * if it is a percentage value, it must be an integer.
     *
     * @return float
     */
    public function getModifierValue()
    {
        return $this->value;
    }

    /**
     * Returns true in case the discount is percentage.
     *
     * @return bool
     */
    public function isPercentage()
    {
        return $this->percentage;
    }

    /**
     * Returns discount description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return $this->additionalAttributes;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getDiscountCode(),
            'modifier' => $this->getModifierValue(),
            'is_percentage' => $this->isPercentage(),
            'description' => $this->getDescription(),
            'additional_attributes' => $this->getAdditionalAttributes()->toArray(),
        ];
    }

    /**
     * Generate a random key.
     *
     * @return string
     */
    protected static function generateKey()
    {
        return hash('adler32', microtime(true), false);
    }

    public function apply(CalculableContract $calculable): float
    {
        // TODO: Implement apply() method.
    }
}
