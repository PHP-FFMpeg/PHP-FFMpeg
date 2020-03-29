<?php

namespace FFMpeg\Filters\AdvancedMedia;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\PadFilter;
use FFMpeg\Filters\Video\WatermarkFilter;
use FFMpeg\Media\AdvancedMedia;

class ComplexFilters
{
    /**
     * @var AdvancedMedia
     */
    protected $media;

    /**
     * ComplexFilters constructor.
     *
     * @param AdvancedMedia $media
     */
    public function __construct(AdvancedMedia $media)
    {
        $this->media = $media;
    }

    /**
     * @param string $in
     * @param string $parameters
     * @param string $out
     *
     * @return ComplexFilters
     */
    public function custom($in, $parameters, $out)
    {
        $this->media->addFilter($in, new CustomComplexFilter($parameters), $out);
        return $this;
    }

    /**
     * Adds padding (black bars) to a video.
     *
     * @param string    $in
     * @param Dimension $dimension
     * @param string    $out
     *
     * @return ComplexFilters
     */
    public function pad($in, Dimension $dimension, $out)
    {
        $this->media->addFilter($in, new PadFilter($dimension), $out);
        return $this;
    }

    /**
     * Adds a watermark image to a video.
     *
     * @param string $in
     * @param string $imagePath
     * @param string $out
     * @param array  $coordinates
     *
     * @return $this
     */
    public function watermark($in, $imagePath, $out, array $coordinates = array())
    {
        $this->media->addFilter($in, new WatermarkFilter($imagePath, $coordinates), $out);
        return $this;
    }

    /**
     * Apply "xstack" filter.
     * Warning: this filter is supported starting from 4.1 ffmpeg version.
     *
     * @param string $in
     * @param string $layout
     * @param int    $inputsCount
     * @param string $out
     *
     * @return ComplexFilters
     * @see https://ffmpeg.org/ffmpeg-filters.html#xstack
     */
    public function xStack($in, $layout, $inputsCount, $out)
    {
        $this->media->addFilter($in, new XStackFilter($layout, $inputsCount), $out);
        return $this;
    }

    /**
     * This filter build various types of computed inputs.
     *
     * @param string      $out
     * @param string|null $type
     * @param string|null $size
     * @param string|null $duration
     * @param string|null $sar
     * @param string|null $rate
     * @param string|null $level
     * @param string|null $color
     * @param int|null    $alpha
     * @param float|null  $decimals
     *
     * @return ComplexFilters
     * @see https://ffmpeg.org/ffmpeg-filters.html#allrgb_002c-allyuv_002c-color_002c-haldclutsrc_002c-nullsrc_002c-pal75bars_002c-pal100bars_002c-rgbtestsrc_002c-smptebars_002c-smptehdbars_002c-testsrc_002c-testsrc2_002c-yuvtestsrc
     */
    public function testSrc(
        $out,
        $type = TestSrcFilter::TESTSRC,
        $size = '320x240',
        $duration = null,
        $sar = null,
        $rate = null,
        $level = null,
        $color = null,
        $alpha = null,
        $decimals = null
    ) {
        $this->media->addFilter('',
            new TestSrcFilter($type, $size, $duration, $sar, $rate, $level, $color, $alpha, $decimals), $out);
        return $this;
    }

    /**
     * Apply "anullsrc" filter.
     *
     * @param string      $out
     * @param string|null $channelLayout
     * @param int|null    $sampleRate
     * @param int|null    $nbSamples
     *
     * @return ComplexFilters
     * @see https://ffmpeg.org/ffmpeg-filters.html#anullsrc
     */
    public function aNullSrc(
        $out,
        $channelLayout = null,
        $sampleRate = null,
        $nbSamples = null
    ) {
        $this->media->addFilter('', new ANullSrcFilter($channelLayout, $sampleRate, $nbSamples), $out);
        return $this;
    }

    /**
     * Apply "sine" filter.
     *
     * @param             $out
     * @param string      $duration
     * @param int|null    $frequency
     * @param string|null $beep_factor
     * @param int|null    $sample_rate
     * @param string|null $samples_per_frame
     *
     * @return $this
     * @see https://ffmpeg.org/ffmpeg-filters.html#sine
     */
    public function sine(
        $out,
        $duration,
        $frequency = null,
        $beep_factor = null,
        $sample_rate = null,
        $samples_per_frame = null
    ) {
        $this->media->addFilter('',
            new SineFilter($duration, $frequency, $beep_factor, $sample_rate, $samples_per_frame), $out);
        return $this;
    }
}
