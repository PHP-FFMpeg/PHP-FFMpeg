<?php

namespace FFMpeg\Filters\ComplexMedia;

use FFMpeg\Media\ComplexMedia;

/**
 * @see https://ffmpeg.org/ffmpeg-filters.html#anullsrc
 */
class ANullSrcFilter extends AbstractComplexFilter
{
    /**
     * @var string|null
     */
    private $channelLayout;

    /**
     * @var int|null
     */
    private $sampleRate;

    /**
     * @var int|null
     */
    private $nbSamples;

    /**
     * ANullSrcComplexFilter constructor.
     *
     * @param string|null $channelLayout
     * @param int|null    $sampleRate
     * @param int|null    $nbSamples
     * @param int         $priority
     */
    public function __construct(
        $channelLayout = null,
        $sampleRate = null,
        $nbSamples = null,
        $priority = 0
    ) {
        parent::__construct($priority);
        $this->channelLayout = $channelLayout;
        $this->sampleRate = $sampleRate;
        $this->nbSamples = $nbSamples;
    }

    /**
     * {@inheritdoc}
     */
    public function applyComplex(ComplexMedia $media)
    {
        return array(
            '-filter_complex',
            'anullsrc' . $this->buildFilterOptions(array(
                'channel_layout' => $this->channelLayout,
                'sample_rate' => $this->sampleRate,
                'nb_samples' => $this->nbSamples,
            ))
        );
    }
}
