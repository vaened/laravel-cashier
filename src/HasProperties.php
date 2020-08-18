<?php
/**
 * Created by enea dhack - 29/09/2017 02:44 PM.
 */

namespace Enea\Cashier;

trait HasProperties
{
    protected array $properties = [];

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function hasProperty(string $key): bool
    {
        return array_key_exists($key, $this->properties);
    }

    public function putProperty(string $key, $value): void
    {
        $this->properties[$key] = $value;
    }

    public function getProperty(string $key)
    {
        return $this->properties[$key] ?? null;
    }

    public function removeProperty(string $key): void
    {
        unset($this->properties[$key]);
    }
}
