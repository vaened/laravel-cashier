<?php
/**
 * Created by enea dhack - 29/09/2017 02:44 PM
 */

namespace Enea\Cashier;

/**
 * Trait HasAttributes
 *
 * @package Enea\Cashier\Contracts
 *
 * Properties
 *
 * @property  \Illuminate\Support\Collection $additionalAttributes;
 * @method   \Illuminate\Support\Collection getAdditionalAttributes();
 */
trait HasAttributes
{
    /**
     * Check if the attribute exists.
     *
     * @param string|int $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        return isset($this->getAdditionalAttributes()[$key]);
    }

    /**
     * Add new attribute.
     *
     * @param string|int $key
     * @param mixed $value
     * @return void
     */
    public function putAttribute($key, $value)
    {
        $this->getAdditionalAttributes()->put($key, $value);
    }

    /**
     * Remove a attribute.
     *
     * @param string|int $key
     * @return void
     */
    public function removeAttribute($key)
    {
        $this->getAdditionalAttributes()->forget($key);
    }
}