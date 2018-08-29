<?php
declare (strict_types = 1);
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

    /**
     * @var float
     */
    private $width;

    /**
     * @var float
     */
    private $height;

    /**
     * @param   float $width
     * @param   float $height
     *
     * @throws InvalidArgumentException when one of the parameteres is invalid (not positive)
     */
    public function __construct(float $width, float $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw new InvalidArgumentException('Both width and height should be positive numbers!');
        }

        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Returns width.
     *
     * @return float
     */
    public function getWidth() : float
    {
        return $this->width;
    }

    /**
     * Returns height.
     *
     * @return float
     */
    public function getHeight() : float
    {
        return $this->height;
    }

    /**
     * Returns the ratio.
     *
     * @param bool $forceStandards Whether or not force the use of standards ratios;
     *
     * @return AspectRatio
     */
    public function getRatio(bool $forceStandards = true) : AspectRatio
    {
        return AspectRatio::create($this, $forceStandards);
    }
}
