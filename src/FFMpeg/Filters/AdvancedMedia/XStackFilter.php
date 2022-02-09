<?php

namespace FFMpeg\Filters\AdvancedMedia;

use FFMpeg\Media\AdvancedMedia;

/**
 * "xstack" filter.
 * This filter helps you to create a collage from the given videos.
 * This filter is supported starting from 4.1 ffmpeg version.
 * (On early versions you can use combinations of hstack and vstack filters).
 *
 * @see https://ffmpeg.org/ffmpeg-filters.html#xstack
 */
class XStackFilter extends AbstractComplexFilter
{
    public const LAYOUT_2X2 = '0_0|0_h0|w0_0|w0_h0';
    public const LAYOUT_1X4 = '0_0|0_h0|0_h0+h1|0_h0+h1+h2';
    public const LAYOUT_3X3 = '0_0|0_h0|0_h0+h1|w0_0|w0_h0|w0_h0+h1|w0+w3_0|w0+w3_h0|w0+w3_h0+h1';
    public const LAYOUT_4X4 = '0_0|0_h0|0_h0+h1|0_h0+h1+h2|w0_0|w0_h0|w0_h0+h1|w0_h0+h1+h2|w0+w4_0|w0+w4_h0|w0+w4_h0+h1|w0+w4_h0+h1+h2|w0+w4+w8_0|w0+w4+w8_h0|w0+w4+w8_h0+h1|w0+w4+w8_h0+h1+h2';

    /**
     * @var string
     */
    private $layout;

    /**
     * @var int
     */
    private $inputsCount;

    /**
     * CustomComplexFilter constructor.
     *
     * @param string $layout
     * @param int    $inputsCount
     * @param int    $priority
     */
    public function __construct($layout, $inputsCount, $priority = 0)
    {
        parent::__construct($priority);
        $this->layout = $layout;
        $this->inputsCount = $inputsCount;
    }

    /**
     * @param int $count
     *
     * @return string
     */
    public static function getInputByCount($count)
    {
        $result = '';
        for ($i = 0; $i < $count; ++$i) {
            $result .= '['.$i.':v]';
        }

        return $result;
    }

    /**
     * Get name of the filter.
     *
     * @return string
     */
    public function getName()
    {
        return 'xstack';
    }

    /**
     * Get minimal version of ffmpeg starting with which this filter is supported.
     *
     * @return string
     */
    public function getMinimalFFMpegVersion()
    {
        return '4.1';
    }

    /**
     * {@inheritdoc}
     */
    public function applyComplex(AdvancedMedia $media)
    {
        return [
            '-filter_complex',
            $this->getName().$this->buildFilterOptions([
                'inputs' => $this->inputsCount,
                'layout' => $this->layout,
            ]),
        ];
    }
}
