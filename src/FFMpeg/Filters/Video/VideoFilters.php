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

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\Point;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Audio\AudioFilters;
use FFMpeg\Filters\Audio\AudioResamplableFilter;
use FFMpeg\Media\Video;

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
     * @param bool      $forceStandards
     *
     * @return VideoFilters
     */
    public function resize(Dimension $dimension, string $mode = ResizeFilter::RESIZEMODE_FIT, bool $forceStandards = true)
    {
        $this->media->addFilter(new ResizeFilter($dimension, $mode, $forceStandards));

        return $this;
    }

    /**
     * Changes the video framerate.
     *
     * @param FrameRate $framerate
     * @param int      $gop
     *
     * @return VideoFilters
     */
    public function framerate(FrameRate $framerate, int $gop)
    {
        $this->media->addFilter(new FrameRateFilter($framerate, $gop));

        return $this;
    }

    /**
     * Extract multiple frames from the video
     *
     * @param   string    $frameRate
     * @param   string    $destinationFolder
     *
     * @return $this
     */
    public function extractMultipleFrames(string $frameRate = ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC, string $destinationFolder = __DIR__)
    {
        $this->media->addFilter(new ExtractMultipleFramesFilter($frameRate, $destinationFolder));

        return $this;
    }

    /**
     * Synchronizes audio and video.
     *
     * @return VideoFilters
     */
    public function synchronize(): VideoFilters
    {
        $this->media->addFilter(new SynchronizeFilter());

        return $this;
    }

    /**
     * Clips (cuts) the video starting at `$start`, optionally until the time of `$duration`.
     *
     * @param TimeCode $start
     * @param TimeCode|null $duration
     *
     * @return VideoFilters
     */
    public function clip(TimeCode $start, ?TimeCode $duration = null)
    {
        $this->media->addFilter(new ClipFilter($start, $duration));

        return $this;
    }

    /**
     * Resamples the audio file.
     *
     * @param int $rate
     *
     * @return AudioFilters
     */
    public function audioResample($rate)
    {
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }

    /**
     * Adds padding (black bars) to a video.
     *
     * @param Dimension $dimension
     *
     * @return VideoFilters
     */
    public function pad(Dimension $dimension)
    {
        $this->media->addFilter(new PadFilter($dimension));

        return $this;
    }

    public function rotate($angle)
    {
        $this->media->addFilter(new RotateFilter($angle, 30));

        return $this;
    }

    /**
     * Crops the video
     *
     * @param Point $point
     * @param Dimension $dimension
     *
     * @return VideoFilters
     */
    public function crop(Point $point, Dimension $dimension)
    {
        $this->media->addFilter(new CropFilter($point, $dimension));

        return $this;
    }

    /**
     * @param string $imagePath
     * @param array  $coordinates
     *
     * @return $this
     */
    public function watermark($imagePath, array $coordinates = [])
    {
        $this->media->addFilter(new WatermarkFilter($imagePath, $coordinates));

        return $this;
    }

    /**
     * Applies a custom filter: -vf foo bar
     *
     * @param string    $parameters
     *
     * @return VideoFilters
     */
    public function custom($parameters)
    {
        $this->media->addFilter(new CustomFilter($parameters));

        return $this;
    }
}
