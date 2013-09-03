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
use FFMpeg\Filters\Audio\AudioResamplableFilter;
use FFMpeg\Filters\Audio\AudioMonoFilter;
use FFMpeg\Filters\Audio\AudioVolumeFilter;
use FFMpeg\Filters\Audio\AudioFilters;

class VideoFilters extends AudioFilters
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
     * @return VideoFilters
     */
    public function resize(Dimension $dimension, $mode = ResizeFilter::RESIZEMODE_FIT, $forceStandards = true)
    {
        $this->media->addFilter(new ResizeFilter($dimension, $mode, $forceStandards));

        return $this;
    }

    /**
     * Changes the video framerate.
     *
     * @param FrameRate $framerate
     * @param type      $gop
     *
     * @return VideoFilters
     */
    public function framerate(FrameRate $framerate, $gop)
    {
        $this->media->addFilter(new FrameRateFilter($framerate, $gop));

        return $this;
    }

    /**
     * Synchronizes audio and video.
     *
     * @return VideoFilters
     */
    public function synchronize()
    {
        $this->media->addFilter(new SynchronizeFilter());

        return $this;
    }

    /**
     * Resamples the audio file.
     *
     * @param Integer $rate
     *
     * @return AudioFilters
     */
    public function audioResample($rate)
    {
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }
    
    /**
     * Changes the video pixelformat
     *
     * @param Integer $pixel_format
     *
     * @return VideoFilters
     */
    public function pixelformat($pixel_format)
    {
        $this->media->addFilter(new PixelFormatFilter($pixel_format));

        return $this;
    }
    
    
    /**
     * Use flag faststart.
     *
     * @return VideoFilters
     */
    public function faststart()
    {
        $this->media->addFilter(new FaststartFilter());

        return $this;
    }
    
    /**
     * Up/Down the audio volume
     *
     * @param Integer $volume
     *
     * @return AudioFilters
     */
    public function audioVolume($volume)
    {
        $this->media->addFilter(new AudioVolumeFilter($volume));

        return $this;
    }
    
    
    /**
     * Force mono output
     *
     * @return AudioFilters
     */
    public function audioMono()
    {
        $this->media->addFilter(new AudioMonoFilter());

        return $this;
    }

}
