<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Media\Video;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;

class VideoFilters
{
    private $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Resizes a video to a given dimension
     *
     * @param Dimension $dimension
     * @param string    $mode
     * @param Boolean   $forceStandards
     *
     * @return VideoFilters
     */
    public function resize(Dimension $dimension, $mode = ResizeFilter::RESIZEMODE_FIT, $forceStandards = true)
    {
        $this->video->addFilter(new ResizeFilter($dimension, $mode, $forceStandards));

        return $this;
    }

    /**
     * Resamples the video to the given framerate.
     *
     * @param FrameRate $framerate
     * @param type      $gop
     *
     * @return VideoFilters
     */
    public function resample(FrameRate $framerate, $gop)
    {
        $this->video->addFilter(new VideoResampleFilter($framerate, $gop));

        return $this;
    }

    /**
     * Synchronizes audio and video.
     *
     * @return VideoFilters
     */
    public function synchronize()
    {
        $this->video->addFilter(new SynchronizeFilter());

        return $this;
    }
}
