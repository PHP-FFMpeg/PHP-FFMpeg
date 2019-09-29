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

/**
 * Dimension object, used for manipulating width and height couples
 */
class Dimension
{
    /** @var int */
    private $width;
    /** @var int */
    private $height;

    /**
     * @param   int     $width
     * @param   int     $height
     *
     * @throws InvalidArgumentException when one of the parameteres is invalid
     */
    public function __construct(int $width, int $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw new InvalidArgumentException('Width and height should be positive int when constructing a dimension.');
        }

        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Returns width.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Returns height.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Returns the ratio.
     *
     * @param bool $forceStandards Whether or not force the use of standards ratios.
     *
     * @return AspectRatio
     */
    public function getRatio(bool $forceStandards = true): AspectRatio
    {
        return AspectRatio::create($this, $forceStandards);
    }
}
