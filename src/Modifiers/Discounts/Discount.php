<?php
/**
 * Created by enea dhack - 29/09/2017 12:02 PM
 */

namespace Enea\Cashier\Modifiers\Discounts;

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
    protected $percentage;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * Discount constructor.
     *
     * @param string|int $key
     * @param string $description
     * @param int $percentage
     * @param Collection $attributes
     */
    public function __construct($key, $description, $percentage, Collection $attributes = null)
    {
        $this->key = $key;
        $this->description = $description;
        $this->percentage = $percentage;
        $this->attributes = $attributes ?: collect();
    }

    /**
     * Returns a new instance.
     *
     * @param $key
     * @param $description
     * @param $percentage
     * @param Collection $attributes
     * @return static
     */
    static public function make($key, $description, $percentage, Collection $attributes = null)
    {
        return new static($key, $description, $percentage, $attributes);
    }

    /**
     * Returns a new instance.
     *
     * @param $description
     * @param $percentage
     * @param Collection $attributes
     * @return static
     */
    static public function generate($description, $percentage, Collection $attributes = null)
    {
        $key = hash('adler32', time(), false);
        return static::make($key, $description, $percentage, $attributes);
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
     * Returns discount  percentage.
     *
     * @return int
     */
    public function getPercentage()
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
     * @return \Illuminate\Support\Collection
     *
     * @return array
     * */
    public function getAdditionalAttributes()
    {
        return $this->attributes;
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
            'percentage' => $this->getPercentage(),
            'description' => $this->getDescription(),
            'additional_attributes' => $this->getAdditionalAttributes()->toArray(),
        ];
    }
}
