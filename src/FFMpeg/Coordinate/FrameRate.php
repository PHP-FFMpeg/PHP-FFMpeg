<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Coordinate;

use FFMpeg\Exception\InvalidArgumentException;

/**
 * Helper class for dealing with framerates
 */
class FrameRate
{

    /**
     * @var float
     */
    private $value;

    /**
     * Initalizes a new framerate object
     *
     * @param float $value
     */
    public function __construct(float $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Invalid frame rate, must be a positive integer.');
        }

        $this->value = $value;
    }

    /**
     * Overwrites the value.
     *
     * @param  float $value The new value
     * @return void
     */
    public function setValue(float $value) : void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Invalid frame rate, must be a positive integer.');
        }
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getValue() : float
    {
        return $this->value;
    }
}
