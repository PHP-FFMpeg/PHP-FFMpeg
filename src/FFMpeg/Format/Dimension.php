<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format;

use FFMpeg\Exception\InvalidArgumentException;

/**
 * Dimension object, used for manipulating width and height couples
 *
 * @author Romain Neutron imprec@gmail.com
 */
class Dimension
{
    protected $width;
    protected $height;

    /**
     * Constructor
     *
     * @param  integer                  $width
     * @param  integer                  $height
     * @throws InvalidArgumentException when one of the parameteres is invalid
     */
    public function __construct($width, $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw InvalidArgumentException('Width and height should be positive integer');
        }

        $this->width = (int) $width;
        $this->height = (int) $height;
    }

    /**
     * Return width
     *
     * @return width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Return height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }
}
