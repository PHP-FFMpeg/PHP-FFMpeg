<?php

namespace FFMpeg\Filters\AdvancedMedia;

use FFMpeg\Media\AdvancedMedia;

/**
 * @see https://ffmpeg.org/ffmpeg-filters.html#sine
 */
class SineFilter extends AbstractComplexFilter
{
    /**
     * @var int|null
     */
    private $frequency;

    /**
     * @var string|null
     */
    private $beep_factor;

    /**
     * @var int|null
     */
    private $sample_rate;

    /**
     * @var string
     */
    private $duration;

    /**
     * @var string|null
     */
    private $samples_per_frame;

    /**
     * SineComplexFilter constructor.
     *
     * @param string      $duration
     * @param int|null    $frequency
     * @param string|null $beep_factor
     * @param int|null    $sample_rate
     * @param string|null $samples_per_frame
     * @param int         $priority
     */
    public function __construct($duration, $frequency, $beep_factor, $sample_rate, $samples_per_frame, $priority = 0)
    {
        parent::__construct($priority);
        $this->duration = $duration;
        $this->frequency = $frequency;
        $this->beep_factor = $beep_factor;
        $this->sample_rate = $sample_rate;
        $this->samples_per_frame = $samples_per_frame;
    }

    /**
     * Get name of the filter.
     *
     * @return string
     */
    public function getName()
    {
        return 'sine';
    }

    /**
     * Get minimal version of ffmpeg starting with which this filter is supported.
     *
     * @return string
     */
    public function getMinimalFFMpegVersion()
    {
        return '2.0';
    }

    /**
     * Apply the complex filter to the given media.
     *
     * @return string[] an array of arguments
     */
    public function applyComplex(AdvancedMedia $media)
    {
        return [
            '-filter_complex',
            $this->getName().$this->buildFilterOptions([
                'frequency' => $this->frequency,
                'beep_factor' => $this->beep_factor,
                'sample_rate' => $this->sample_rate,
                'duration' => $this->duration,
                'samples_per_frame' => $this->samples_per_frame,
            ]),
        ];
    }
}
