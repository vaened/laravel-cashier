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
 * @property  \Illuminate\Support\Collection attributes;
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
        return isset($this->attributes[$key]);
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
        $this->attributes->put($key, $value);
    }

    /**
     * Remove a attribute.
     *
     * @param string|int $key
     * @return void
     */
    public function removeAttribute($key)
    {
        $this->attributes->forget($key);
    }
}