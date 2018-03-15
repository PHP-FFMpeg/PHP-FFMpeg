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

/**
 * Represents a point in a media
 */
class Point
{
    /**
     * @var int|string
     */
    private $x;

    /**
     * @var int|string
     */
    private $y;

    /**
     * @var bool
     */
    private $isDynamic;

    /**
     * Creates a new Point
     *
     * @param int|string $x       X-coordinate / Math expression for X
     * @param int|string $y       Y-coordinate / Math expression for Y
     * @param bool       $dynamic Whether the point is dynamic based on a math expression
     */
    public function __construct($x, $y, bool $dynamic = false)
    {
        if ($dynamic) {
            $this->x = $x;
            $this->y = $y;
        } else {
            $this->x = (int) $x;
            $this->y = (int) $y;
        }

        $this->isDynamic = $dynamic;
    }

    /**
     * @return int|string
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int|string
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Returns whether this point was set dynamic
     *
     * @return bool
     * @since  1.0.0
     */
    public function isDynamic(): bool
    {
        return $this->isDynamic;
    }
}
