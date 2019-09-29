<?php

declare(strict_types=1);

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Filters\TPriorityFilter;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;

class FrameRateFilter implements VideoFilterInterface
{
    use TPriorityFilter;

    /** @var FrameRate */
    private $rate;

    /** @var int */
    private $gop;

    /** @var int */
    private $priority;

    public function __construct(FrameRate $rate, int $gop, int $priority = 0)
    {
        $this->rate = $rate;
        $this->gop = $gop;
        $this->priority = $priority;
    }

    /**
     * Returns the frame rate.
     *
     * @return FrameRate
     */
    public function getFrameRate(): FrameRate
    {
        return $this->rate;
    }

    /**
     * Returns the GOP size.
     *
     * @see https://wikipedia.org/wiki/Group_of_pictures
     *
     * @return int
     */
    public function getGOP(): int
    {
        return $this->gop;
    }

    /**
     * @inheritDoc
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $commands = ['-r', $this->rate->getValue()];

        // see http://sites.google.com/site/linuxencoding/x264-ffmpeg-mapping
        if ($format->supportBFrames()) {
            $commands[] = '-b_strategy';
            $commands[] = '1';
            $commands[] = '-bf';
            $commands[] = '3';
            $commands[] = '-g';
            $commands[] = $this->gop;
        }

        return $commands;
    }
}
