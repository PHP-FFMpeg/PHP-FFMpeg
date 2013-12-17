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

use FFMpeg\Exception\InvalidArgumentException;

abstract class AbstractData implements \Countable
{
    private $properties;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Returns true if data has property.
     *
     * @param  string  $property
     * @return Boolean
     */
    public function has($property)
    {
        return isset($this->properties[$property]);
    }

    /**
     * Returns the property value given its name.
     *
     * @param  string $property
     * @return mixed
     *
     * @throws InvalidArgumentException In case the data does not have the property
     */
    public function get($property)
    {
        if (!isset($this->properties[$property])) {
            throw new InvalidArgumentException(sprintf('Invalid property `%s`.', $property));
        }

        return $this->properties[$property];
    }

    /**
     * Sets the property value given its name.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return AbstractData
     */
    public function set($property, $value)
    {
        $this->properties[$property] = $value;

        return $this;
    }

    /**
     * Returns all property names.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->properties);
    }

    /**
     * Returns all properties and their values.
     *
     * @return array
     */
    public function all()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->properties);
    }
}
