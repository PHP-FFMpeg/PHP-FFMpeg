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

    public function resize(Dimension $dimension, $mode = ResizeFilter::RESIZEMODE_FIT, $forceStandards = true)
    {
        $this->video->addFilter(new ResizeFilter($dimension, $this->video->getFFProbe(), $mode, $forceStandards));

        return $this;
    }

    public function resample(FrameRate $framerate, $gop)
    {
        return $this->video->addFilter(new VideoResampleFilter($framerate, $gop));
    }
}
