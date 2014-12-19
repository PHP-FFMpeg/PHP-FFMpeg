<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Options\Video;

use FFMpeg\Media\Video;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Options\Audio\AudioResamplableOption;
use FFMpeg\Options\Audio\AudioOptions;

class VideoOptions extends AudioOptions
{
    public function __construct(Video $media)
    {
        parent::__construct($media);
    }

    /**
     * Resizes a video to a given dimension.
     *
     * @param Dimension $dimension
     * @param string    $mode
     * @param Boolean   $forceStandards
     *
     * @return VideoOptions
     */
    public function resize(Dimension $dimension, $mode = ResizeOption::RESIZEMODE_FIT, $forceStandards = true)
    {
        $this->media->addOption(new ResizeOption($dimension, $mode, $forceStandards));

        return $this;
    }

    /**
     * Changes the video framerate.
     *
     * @param FrameRate $framerate
     * @param type      $gop
     *
     * @return VideoOptions
     */
    public function framerate(FrameRate $framerate, $gop)
    {
        $this->media->addOption(new FrameRateOption($framerate, $gop));

        return $this;
    }

    /**
     * Synchronizes audio and video.
     *
     * @return VideoOptions
     */
    public function synchronize()
    {
        $this->media->addOption(new SynchronizeOption());

        return $this;
    }

    /**
     * Clips (cuts) the video.
     *
     * @param TimeCode $start
     * @param TimeCode $duration
     *
     * @return VideoOptions
     */
    public function clip($start, $duration = null)
    {
        $this->media->addOption(new ClipOption($start, $duration));

        return $this;
    }

    /**
     * Resamples the audio file.
     *
     * @param Integer $rate
     *
     * @return AudioOptions
     */
    public function audioResample($rate)
    {
        $this->media->addOption(new AudioResamplableOption($rate));

        return $this;
    }
}
