<?php

declare(strict_types=1);

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

class Point
{
    /** @var int|string */
    private $x;
    /** @var int|string */
    private $y;
    /** @var bool */
    private $isDynamicPoint;

    public function __construct($x, $y, bool $isDynamicPoint = false)
    {
        if ($isDynamicPoint) {
            if (!\is_string($x) || !\is_string($y)) {
                throw new InvalidArgumentException(
                    'When creating dynamic points, the expression of the coordinates must be given as a string.'
                );
            }

            $this->x = $x;
            $this->y = $y;
        } else {
            if (!\is_int($x) || !\is_int($y)) {
                throw new InvalidArgumentException(
                    'When creating non-dynamic points, the expression of the coordinates must be given as ints.'
                );
            }

            $this->x = (int) $x;
            $this->y = (int) $y;
        }

        $this->isDynamicPoint = $isDynamicPoint;
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
     * @return bool
     */
    public function isDynamicPoint(): bool
    {
        return $this->isDynamicPoint;
    }
}
