<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Media\Video;
use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Format\VideoInterface;

class PadFilter implements VideoFilterInterface
{
    use TPriorityFilter;

    /** @var Dimension */
    private $dimension;
    /** @var int */
    private $priority;

    public function __construct(Dimension $dimension, int $priority = 0)
    {
        $this->dimension = $dimension;
        $this->priority = $priority;
    }

    /**
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format): array
    {
        $commands = ['-vf'];

        $height = $this->dimension->getHeight();
        $width = $this->dimension->getWidth();
        // hopefully this will ever be correct. I do not know how to debug it :D
        $commands[] = "scale=iw*min({$width}/iw\,{$height}/ih):ih*min({$width}/iw\,{$height}/ih),pad={$width}:{$height}:({$width}-iw)/2:({$height}-ih)/2";

        return $commands;
    }
}
