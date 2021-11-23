<?php

namespace FFMpeg\Filters\AdvancedMedia;

use FFMpeg\Media\AdvancedMedia;

/**
 * This filter builds various types of computed inputs.
 *
 * @see https://ffmpeg.org/ffmpeg-filters.html#allrgb_002c-allyuv_002c-color_002c-haldclutsrc_002c-nullsrc_002c-pal75bars_002c-pal100bars_002c-rgbtestsrc_002c-smptebars_002c-smptehdbars_002c-testsrc_002c-testsrc2_002c-yuvtestsrc
 */
class TestSrcFilter extends AbstractComplexFilter
{
    /**
     * Source returns frames of size 4096x4096 of all rgb colors.
     */
    public const ALLRGB = 'allrgb';

    /**
     * Source returns frames of size 4096x4096 of all yuv colors.
     */
    public const ALLYUV = 'allyuv';

    /**
     * Source provides an uniformly colored input.
     */
    public const COLOR = 'color';

    /**
     * Source provides an identity Hald CLUT.
     */
    public const HALDCLUTSRC = 'haldclutsrc';

    /**
     * Source returns unprocessed video frames.
     * It is mainly useful to be employed in analysis / debugging tools,
     * or as the source for filters which ignore the input data.
     */
    public const NULLSRC = 'nullsrc';

    /**
     * Source generates a color bars pattern, based on EBU PAL recommendations with 75% color levels.
     */
    public const PAL75BARS = 'pal75bars';

    /**
     * Source generates a color bars pattern, based on EBU PAL recommendations with 100% color levels.
     */
    public const PAL100BARS = 'pal100bars';

    /**
     * Source generates an RGB test pattern useful for detecting RGB vs BGR issues.
     * You should see a red, green and blue stripe from top to bottom.
     */
    public const RGBTESTSRC = 'rgbtestsrc';

    /**
     * Source generates a color bars pattern, based on the SMPTE Engineering Guideline EG 1-1990.
     */
    public const SMPTEBARS = 'smptebars';

    /**
     * Source generates a color bars pattern, based on the SMPTE RP 219-2002.
     */
    public const SMPTEHDBARS = 'smptehdbars';

    /**
     * Source generates a test video pattern, showing a color pattern, a scrolling gradient and a timestamp.
     * This is mainly intended for testing purposes.
     */
    public const TESTSRC = 'testsrc';

    /**
     * Source is similar to testsrc, but supports more pixel formats instead of just rgb24.
     * This allows using it as an input for other tests without requiring a format conversion.
     */
    public const TESTSRC2 = 'testsrc2';

    /**
     * Source generates an YUV test pattern. You should see a y, cb and cr stripe from top to bottom.
     */
    public const YUVTESTSRC = 'yuvtestsrc';

    /**
     * @var string|null
     */
    private $type;

    /**
     * Specify the level of the Hald CLUT, only available in the haldclutsrc source.
     * A level of N generates a picture of N*N*N by N*N*N pixels to be used as identity matrix for 3D lookup tables.
     * Each component is coded on a 1/(N*N) scale.
     *
     * @var string|null
     */
    private $level;

    /**
     * Specify the color of the source, only available in the color source.
     *
     * @var string|null
     */
    private $color;

    /**
     * Specify the size of the sourced video.
     * This option is not available with the allrgb, allyuv, and haldclutsrc filters.
     *
     * @var string|null
     */
    private $size;

    /**
     * Specify the frame rate of the sourced video, as the number of frames generated per second.
     * It has to be a string in the format frame_rate_num/frame_rate_den, an integer number,
     * a floating point number or a valid video frame rate abbreviation. The default value is "25".
     *
     * @var string|null
     */
    private $rate;

    /**
     * Set the duration of the sourced video.
     * If not specified, or the expressed duration is negative, the video is supposed to be generated forever.
     *
     * @var string|null
     */
    private $duration;

    /**
     * Set the sample aspect ratio of the sourced video.
     *
     * @var string|null
     */
    private $sar;

    /**
     * Specify the alpha (opacity) of the background, only available in the testsrc2 source.
     * The value must be between 0 (fully transparent) and 255 (fully opaque, the default).
     *
     * @var int|null
     */
    private $alpha;

    /**
     * Set the number of decimals to show in the timestamp, only available in the testsrc source.
     * The displayed timestamp value will correspond to the original timestamp value multiplied
     * by the power of 10 of the specified value. Default value is 0.
     *
     * @var float|null
     */
    private $decimals;

    /**
     * TestSrcComplexFilter constructor.
     *
     * @param string|null $type
     * @param string|null $size
     * @param string|null $duration
     * @param string|null $sar
     * @param string|null $rate
     * @param string|null $level
     * @param string|null $color
     * @param int|null    $alpha
     * @param float|null  $decimals
     * @param int|null    $priority
     */
    public function __construct(
        $type = self::TESTSRC,
        $size = '320x240',
        $duration = null,
        $sar = null,
        $rate = null,
        $level = null,
        $color = null,
        $alpha = null,
        $decimals = null,
        $priority = 0
    ) {
        parent::__construct($priority);
        $this->type = $type;
        $this->level = $level;
        $this->color = $color;
        $this->size = $size;
        $this->rate = $rate;
        $this->duration = $duration;
        $this->sar = $sar;
        $this->alpha = $alpha;
        $this->decimals = $decimals;
    }

    /**
     * Get name of the filter.
     *
     * @return string
     */
    public function getName()
    {
        return $this->type;
    }

    /**
     * Get minimal version of ffmpeg starting with which this filter is supported.
     *
     * @return string
     */
    public function getMinimalFFMpegVersion()
    {
        switch ($this->type) {
            case self::PAL75BARS:
            case self::PAL100BARS:
                return '4.1';
            case self::YUVTESTSRC:
                return '3.2';
            case self::ALLRGB:
            case self::ALLYUV:
                return '2.8';
            case self::SMPTEHDBARS:
                return '2.0';
            case self::SMPTEBARS:
                return '1.0';
            default:
                return '0.3';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function applyComplex(AdvancedMedia $media)
    {
        return [
            '-filter_complex',
            $this->type.$this->buildFilterOptions([
                'level' => $this->level,
                'color' => $this->color,
                'size' => $this->size,
                'rate' => $this->rate,
                'duration' => $this->duration,
                'sar' => $this->sar,
                'alpha' => $this->alpha,
                'decimals' => $this->decimals,
            ]),
        ];
    }
}
