<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe\DataMapping;

/**
 * Abstract implementation for data
 */
abstract class AbstractData implements \Countable
{

    /**
     * Holds the properties
     *
     * @var mixed[]
     */
    private $properties;

    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     * Returns true if data has property.
     *
     * @param  string $property
     * @return bool
     */
    public function has($property): bool
    {
        return isset($this->properties[$property]);
    }

    /**
     * Returns the property value given its name.
     *
     * @param  string $property
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $property, $default = null)
    {
        if (!$this->has($property)) {
            return $default;
        }

        return $this->properties[$property];
    }

    /**
     * Sets the property value given its name.
     *
     * @param  string $property
     * @param  mixed  $value
     * @return self
     */
    public function set(string $property, $value): self
    {
        $this->properties[$property] = $value;

        return $this;
    }

    /**
     * Returns all property names.
     *
     * @return string[]
     */
    public function keys(): array
    {
        return array_keys($this->properties);
    }

    /**
     * Returns all properties and their values.
     *
     * @return mixed[][]
     */
    public function all()
    {
        return $this->properties;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->properties);
    }
}
