<?php
declare (strict_types = 1);

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
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\Point;
use FFMpeg\Filters\Audio\AudioResamplableFilter;
use FFMpeg\Filters\Audio\AudioFilters;

class VideoFilters extends AudioFilters
{

    /**
     * @inheritDoc
     * @param Video $media The video the filters will be applied to
     */
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
     * @return self
     */
    public function resize(
        Dimension $dimension,
        string $mode = ResizeFilter::RESIZEMODE_FIT,
        bool $forceStandards = true
    ) : self {
        $this->media->addFilter(new ResizeFilter($dimension, $mode, $forceStandards));

        return $this;
    }

    /**
     * Changes the video framerate.
     *
     * @param  FrameRate $framerate
     * @param  int       $gop
     * @return self
     */
    public function framerate(FrameRate $framerate, ? int $gop = null) : self
    {
        $this->media->addFilter(new FrameRateFilter($framerate, $gop));

        return $this;
    }

    /**
     * Extract multiple frames from the video
     *
     * @param string $frameRate
     * @param string $destinationFolder
     *
     * @return self
     */
    public function extractMultipleFrames(
        string $frameRate = ExtractMultipleFramesFilter::FRAMERATE_EVERY_2SEC,
        string $destinationFolder = __DIR__
    ) : self {
        $this->media->addFilter(new ExtractMultipleFramesFilter($frameRate, $destinationFolder));

        return $this;
    }

    /**
     * Synchronizes audio and video.
     *
     * @return self
     */
    public function synchronize() : self
    {
        $this->media->addFilter(new SynchronizeFilter());

        return $this;
    }

    /**
     * Clips (cuts) the video.
     *
     * @param TimeCode $start
     * @param TimeCode $duration
     *
     * @return self
     */
    public function clip(TimeCode $start, ? TimeCode $duration = null) : self
    {
        $this->media->addFilter(new ClipFilter($start, $duration));

        return $this;
    }

    /**
     * Resamples the audio file.
     *
     * @param  int $rate
     * @return self
     */
    public function audioResample(int $rate) : self
    {
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }

    /**
     * Adds padding (black bars) to a video.
     *
     * @param  Dimension $dimension
     * @return self
     */
    public function pad(Dimension $dimension) : self
    {
        $this->media->addFilter(new PadFilter($dimension));

        return $this;
    }

    /**
     * Rotates the video by given degrees.
     *
     * @param  string $angle One of `RotateFilter::ROTATE_[90|180|270]` constants
     * @return self
     */
    public function rotate(string $angle) : self
    {
        // use a high priority
        $this->media->addFilter(new RotateFilter($angle, 30));

        return $this;
    }

    /**
     * Crops the video
     *
     * @param Point     $point
     * @param Dimension $dimension
     *
     * @return self
     */
    public function crop(Point $point, Dimension $dimension) : self
    {
        $this->media->addFilter(new CropFilter($point, $dimension));

        return $this;
    }

    /**
     * @param string $imagePath
     * @param array  $coordinates
     *
     * @return self
     */
    public function watermark(string $imagePath, array $coordinates = []) : self
    {
        $this->media->addFilter(new WatermarkFilter($imagePath, $coordinates));

        return $this;
    }

    /**
     * Applies a custom filter: -vf foo bar
     *
     * @param string $parameters
     *
     * @return self
     */
    public function custom(string $parameters) : self
    {
        $this->media->addFilter(new CustomFilter($parameters));

        return $this;
    }
}
