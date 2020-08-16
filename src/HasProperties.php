<?php
/**
 * Created by enea dhack - 29/09/2017 02:44 PM.
 */

namespace Enea\Cashier;

trait HasAttributes
{
    abstract public function getProperties(): array;

    public function hasProperty(string $key): bool
    {
        return array_key_exists($key, $this->getProperties());
    }

    public function putProperty(string $key, $value): void
    {

        $this->additionalAttributes[$key] = $value;
    }

    public function getProperty(string $key)
    {
        return $this->additionalAttributes[$key] ?? null;
    }

    public function removeProperty(string $key): void
    {
        unset($this->additionalAttributes[$key]);
    }
}
