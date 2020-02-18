<?php

namespace FFMpeg\Filters\ComplexMedia;

use FFMpeg\Media\ComplexMedia;

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
     * Apply the complex filter to the given media.
     *
     * @param ComplexMedia $media
     *
     * @return string[] An array of arguments.
     */
    public function applyComplex(ComplexMedia $media)
    {
        return array(
            '-filter_complex',
            'sine' . $this->buildFilterOptions(array(
                'frequency' => $this->frequency,
                'beep_factor' => $this->beep_factor,
                'sample_rate' => $this->sample_rate,
                'duration' => $this->duration,
                'samples_per_frame' => $this->samples_per_frame,
            ))
        );
    }
}
