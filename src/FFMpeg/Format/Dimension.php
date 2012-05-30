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
 * @author Romain Neutron imprec@gmail.com
 */
class Dimension
{
    protected $width;
    protected $height;

    public function __construct($width, $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw InvalidArgumentException('Width and height should be positive integer');
        }

        $this->width = (int) $width;
        $this->height = (int) $height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }
}
