<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Strime <contact@strime.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;

class PadFilter implements VideoFilterInterface
{

    use TPriorityFilter;

    /**
     * @var Dimension
     */
    private $dimension;

    /**
     * @var integer
     */
    private $priority;

    public function __construct(Dimension $dimension, int $priority = 0) 
    {
        $this->dimension = $dimension;

        $this->setPriority($priority);
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
        $commands = [];

        $commands[] = '-vf';
        $commands[] = 'scale=iw*min(' . $this->dimension->getWidth() . '/iw\,' . $this->dimension->getHeight() .'/ih):ih*min(' . $this->dimension->getWidth() . '/iw\,' . $this->dimension->getHeight() .'/ih),pad=' . $this->dimension->getWidth() . ':' . $this->dimension->getHeight() . ':(' . $this->dimension->getWidth() . '-iw)/2:(' . $this->dimension->getHeight() .'-ih)/2';

        return $commands;
    }
}
